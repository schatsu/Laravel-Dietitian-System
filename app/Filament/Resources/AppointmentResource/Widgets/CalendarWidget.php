<?php

namespace App\Filament\Resources\AppointmentResource\Widgets;

use App\Models\Appointment;
use App\Models\AppointmentSlot;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{
    public function fetchEvents(array $fetchInfo): array
    {
        return Appointment::query()
            ->whereHas('slot', function ($q) use ($fetchInfo) {
                $q->whereBetween('date', [$fetchInfo['start'], $fetchInfo['end']]);
            })
            ->with('slot')
            ->get()
            ->map(function (Appointment $appointment) {
                $slot = $appointment->slot;

                return [
                    'id' => $appointment->id,
                    'title' => $appointment->name ?? 'Randevu',
                    'start' => $slot->date . 'T' . $slot->start_time,
                    'end' => $slot->date . 'T' . $slot->end_time,
                    'color' => 'red',
                ];
            })
            ->toArray();
    }
}
