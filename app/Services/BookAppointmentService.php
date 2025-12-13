<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Zap\Facades\Zap;
use Zap\Models\Schedule;

class BookAppointmentService
{
    protected User $dietitian;

    public function __construct(?User $dietitian = null)
    {
        // Varsayılan olarak ilk super_admin kullanıcıyı diyetisyen olarak kullan
        $this->dietitian = $dietitian ?? User::role('super_admin')->first();
    }

    /**
     * Diyetisyen için müsaitlik takvimi oluştur
     */
    public function setupAvailability(
        array $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
        string $startTime = '09:00',
        string $endTime = '17:00',
        ?int $year = null
    ): Schedule {
        $year = $year ?? Carbon::now()->year;

        return Zap::for($this->dietitian)
            ->named('Çalışma Saatleri')
            ->availability()
            ->forYear($year)
            ->addPeriod($startTime, $endTime)
            ->weekly($days)
            ->save();
    }

    /**
     * Öğle arası veya tatil için zaman bloklama
     */
    public function blockTime(
        string $name,
        string $startTime,
        string $endTime,
        array $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
        ?int $year = null
    ): Schedule {
        $year = $year ?? Carbon::now()->year;

        return Zap::for($this->dietitian)
            ->named($name)
            ->blocked()
            ->forYear($year)
            ->addPeriod($startTime, $endTime)
            ->weekly($days)
            ->save();
    }

    /**
     * Belirli bir tarih aralığını bloklama (izin, tatil vb.)
     */
    public function blockDateRange(
        string $name,
        string $startDate,
        string $endDate,
        string $startTime = '00:00',
        string $endTime = '23:59'
    ): Schedule {
        return Zap::for($this->dietitian)
            ->named($name)
            ->blocked()
            ->between($startDate, $endDate)
            ->addPeriod($startTime, $endTime)
            ->save();
    }

    /**
     * Belirtilen tarih için müsait slotları getir
     * Seans süresi ve buffer zamanı müsaitlik takviminin metadata'sından alınır
     */
    public function getAvailableSlots(string $date, ?int $duration = null, ?int $buffer = null): array
    {
        // Seans ayarlarını metadata'dan al (veya varsayılan değerleri kullan)
        $settings = $this->getSessionSettings();
        $duration = $duration ?? $settings['session_duration'];
        $buffer = $buffer ?? $settings['buffer_time'];

        return $this->dietitian->getBookableSlots($date, $duration, $buffer);
    }

    /**
     * Müsaitlik takviminden seans ayarlarını al
     */
    public function getSessionSettings(): array
    {
        // En güncel aktif müsaitlik takviminin ayarlarını al
        $availability = $this->dietitian->availabilitySchedules()
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($availability && is_array($availability->metadata)) {
            return [
                'session_duration' => (int) ($availability->metadata['session_duration'] ?? 45),
                'buffer_time' => (int) ($availability->metadata['buffer_time'] ?? 15),
            ];
        }

        // Varsayılan değerler
        return [
            'session_duration' => 45,
            'buffer_time' => 15,
        ];
    }

    /**
     * Belirtilen tarihte randevu alınabilir mi kontrol et
     */
    public function isBookableAt(string $date, ?int $duration = null): bool
    {
        $settings = $this->getSessionSettings();
        $duration = $duration ?? $settings['session_duration'];

        return $this->dietitian->isBookableAt($date, $duration);
    }

    /**
     * Randevu oluştur
     */
    public function bookAppointment(
        string $date,
        string $startTime,
        string $endTime,
        array $clientData
    ): Schedule {
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

    /**
     * Randevuyu iptal et
     */
    public function cancelAppointment(int $scheduleId): bool
    {
        $schedule = Schedule::query()->find($scheduleId);

        if ($schedule && $schedule->isAppointment()) {
            $schedule->delete();
            return true;
        }

        return false;
    }

    /**
     * Bir sonraki müsait slotu bul
     */
    public function getNextAvailableSlot(string $fromDate, int $duration = 60, int $buffer = 15): ?array
    {
        return $this->dietitian->getNextBookableSlot($fromDate, $duration, $buffer);
    }

    /**
     * Tarih aralığı için tüm schedule'ları getir
     */
    public function getSchedulesForDateRange(string $startDate, string $endDate)
    {
        return $this->dietitian->schedulesForDateRange($startDate, $endDate)->get();
    }

    /**
     * Sadece randevu tipindeki schedule'ları getir
     */
    public function getAppointments()
    {
        return $this->dietitian->appointmentSchedules()->with('periods')->get();
    }

    /**
     * Sadece müsaitlik tipindeki schedule'ları getir
     */
    public function getAvailabilities()
    {
        return $this->dietitian->availabilitySchedules()->with('periods')->get();
    }

    /**
     * Sadece bloke tipindeki schedule'ları getir
     */
    public function getBlockedSchedules()
    {
        return $this->dietitian->blockedSchedules()->with('periods')->get();
    }

    /**
     * Diyetisyen modelini ayarla
     */
    public function setDietitian(User $dietitian): self
    {
        $this->dietitian = $dietitian;
        return $this;
    }

    /**
     * Mevcut diyetisyeni getir
     */
    public function getDietitian(): User
    {
        return $this->dietitian;
    }
}
