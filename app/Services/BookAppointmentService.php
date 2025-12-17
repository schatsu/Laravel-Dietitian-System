<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Zap\Facades\Zap;
use Zap\Models\Schedule;

class BookAppointmentService
{
    protected User $dietitian;

    public function __construct(?User $dietitian = null)
    {
        $this->dietitian = $dietitian ?? User::role('super_admin')->first();
    }


    public function setupAvailability(
        array  $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
        string $startTime = '09:00',
        string $endTime = '17:00',
        ?int   $year = null
    ): Schedule
    {
        $year = $year ?? Carbon::now()->year;

        return Zap::for($this->dietitian)
            ->named('Çalışma Saatleri')
            ->availability()
            ->forYear($year)
            ->addPeriod($startTime, $endTime)
            ->weekly($days)
            ->save();
    }

    public function blockTime(
        string $name,
        string $startTime,
        string $endTime,
        array  $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
        ?int   $year = null
    ): Schedule
    {
        $year = $year ?? Carbon::now()->year;

        return Zap::for($this->dietitian)
            ->named($name)
            ->blocked()
            ->forYear($year)
            ->addPeriod($startTime, $endTime)
            ->weekly($days)
            ->save();
    }

    public function blockDateRange(
        string $name,
        string $startDate,
        string $endDate,
        string $startTime = '00:00',
        string $endTime = '23:59'
    ): Schedule
    {
        return Zap::for($this->dietitian)
            ->named($name)
            ->blocked()
            ->between($startDate, $endDate)
            ->addPeriod($startTime, $endTime)
            ->save();
    }

    public function getAvailableSlots(string $date, ?int $duration = null, ?int $buffer = null): array
    {
        $settings = $this->getSessionSettings();
        $duration = $duration ?? $settings['session_duration'];
        $buffer = $buffer ?? $settings['buffer_time'];

        $slots = $this->dietitian->getBookableSlots($date, $duration, $buffer);

        $dayOfWeek = strtolower(Carbon::parse($date)->format('l'));

        $isDateBlocked = $this->dietitian->blockedSchedules()
            ->where('is_active', true)
            ->get()
            ->contains(function ($schedule) use ($date, $dayOfWeek) {
                $startDate = $schedule->start_date instanceof Carbon
                    ? $schedule->start_date->format('Y-m-d')
                    : substr($schedule->start_date, 0, 10);
                $endDate = $schedule->end_date instanceof Carbon
                    ? $schedule->end_date->format('Y-m-d')
                    : ($schedule->end_date ? substr($schedule->end_date, 0, 10) : null);

                if ($schedule->is_recurring && $schedule->frequency === 'weekly') {
                    $blockedDays = $schedule->frequency_config['days'] ?? [];
                    if (in_array($dayOfWeek, $blockedDays)) {
                        if ($date >= $startDate && (!$endDate || $date <= $endDate)) {
                            return true;
                        }
                    }
                }

                if (!$schedule->is_recurring) {
                    if ($date >= $startDate && (!$endDate || $date <= $endDate)) {
                        return true;
                    }
                }

                return false;
            });

        if ($isDateBlocked) {
            return array_map(function ($slot) {
                return array_merge($slot, ['available' => false]);
            }, $slots);
        }

        $existingAppointments = $this->dietitian->appointmentSchedules()
            ->with('periods')
            ->get()
            ->flatMap(function ($schedule) use ($date) {
                return $schedule->periods->filter(function ($period) use ($date) {
                    $periodDate = $period->date instanceof Carbon
                        ? $period->date->format('Y-m-d')
                        : (string) $period->date;
                    return $periodDate === $date;
                })->map(function ($period) {
                    $startTime = substr($period->start_time, 0, 5);
                    $endTime = substr($period->end_time, 0, 5);
                    return $startTime . '-' . $endTime;
                });
            })
            ->toArray();

        return array_map(function ($slot) use ($existingAppointments) {
            $slotKey = $slot['start_time'] . '-' . $slot['end_time'];
            $isBooked = in_array($slotKey, $existingAppointments);

            return array_merge($slot, [
                'available' => !$isBooked,
            ]);
        }, $slots);
    }

    public function getSessionSettings(): array
    {
        $availability = $this->dietitian->availabilitySchedules()
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($availability && is_array($availability->metadata)) {
            return [
                'session_duration' => (int)($availability->metadata['session_duration'] ?? 45),
                'buffer_time' => (int)($availability->metadata['buffer_time'] ?? 15),
            ];
        }

        return [
            'session_duration' => 45,
            'buffer_time' => 15,
        ];
    }

    public function isBookableAt(string $date, ?int $duration = null): bool
    {
        $settings = $this->getSessionSettings();
        $duration = $duration ?? $settings['session_duration'];

        return $this->dietitian->isBookableAt($date, $duration);
    }

    public function bookAppointment(
        string $date,
        string $startTime,
        string $endTime,
        array  $clientData
    ): Schedule
    {
        return Zap::for($this->dietitian)
            ->named($clientData['name'] . ' - Randevu')
            ->appointment()
            ->from($date)
            ->addPeriod($startTime, $endTime)
            ->withMetadata([
                'client_name' => $clientData['name'],
                'client_email' => $clientData['email'] ?? null,
                'client_phone' => $clientData['phone'] ?? null,
                'note' => $clientData['note'] ?? null,
                'status' => $clientData['status'] ?? 'pending',
            ])
            ->save();
    }

    public function cancelAppointment(int $scheduleId): bool
    {
        $schedule = Schedule::query()->find($scheduleId);

        if ($schedule && $schedule->isAppointment()) {
            $schedule->delete();
            return true;
        }

        return false;
    }


    public function getNextAvailableSlot(string $fromDate, int $duration = 60, int $buffer = 15): ?array
    {
        return $this->dietitian->getNextBookableSlot($fromDate, $duration, $buffer);
    }

    public function getSchedulesForDateRange(string $startDate, string $endDate): Collection
    {
        return $this->dietitian->schedulesForDateRange($startDate, $endDate)->get();
    }

    public function getAppointments(): Collection
    {
        return $this->dietitian->appointmentSchedules()->with('periods')->get();
    }

    public function getAvailabilities(): Collection
    {
        return $this->dietitian->availabilitySchedules()->with('periods')->get();
    }

    public function getBlockedSchedules(): Collection
    {
        return $this->dietitian->blockedSchedules()->with('periods')->get();
    }

    public function setDietitian(User $dietitian): self
    {
        $this->dietitian = $dietitian;
        return $this;
    }

    public function getDietitian(): User
    {
        return $this->dietitian;
    }
}
