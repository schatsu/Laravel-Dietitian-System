<?php

namespace App\Helpers;

use App\Models\WeeklySchedule;
use App\Models\AppointmentSlot;
use Illuminate\Support\Carbon;

class AppointmentHelper
{
    /**
     * Haftalık programa göre belirli gün sayısı için slot üretir
     *
     * @param int $days Kaç gün ileri için üretilecek (default 30)
     * @return void
     */
    public static function generateSlots(int $days = 30): void
    {
        $startDate = Carbon::today();

        for ($i = 0; $i < $days; $i++) {
            $date = $startDate->copy()->addDays($i);
            $dayOfWeek = $date->dayOfWeek;

            $schedules = WeeklySchedule::query()->where('day_of_week', $dayOfWeek)
                ->where('is_active', true)
                ->get();

            foreach ($schedules as $schedule) {
                $start = Carbon::parse($date->toDateString() . ' ' . $schedule->start_time);
                $end = Carbon::parse($date->toDateString() . ' ' . $schedule->end_time);
                $duration = $schedule->duration;

                while ($start->copy()->addMinutes($duration)->lte($end)) {
                    $slotStart = $start->format('H:i');
                    $slotEnd = $start->copy()->addMinutes($duration)->format('H:i');

                    // Aynı slot var mı kontrol et
                    $exists = AppointmentSlot::query()->where('date', $date->toDateString())
                        ->where('start_time', $slotStart)
                        ->where('end_time', $slotEnd)
                        ->exists();

                    if (!$exists) {
                        AppointmentSlot::query()->create([
                            'weekly_schedule_id' => $schedule->id,
                            'date' => $date->toDateString(),
                            'start_time' => $slotStart,
                            'end_time' => $slotEnd,
                            'is_booked' => false,
                            'is_active' => true,
                        ]);
                    }

                    $start->addMinutes($duration);
                }
            }
        }
    }
}
