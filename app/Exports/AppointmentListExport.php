<?php

namespace App\Exports;

use App\Enums\AppointmentStatusEnum;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Zap\Enums\ScheduleTypes;
use Zap\Models\Schedule;

class AppointmentListExport implements FromCollection, WithHeadings
{
    /**
    * @return Collection
    */
    public function collection(): Collection
    {
        $dietitian = User::role('super_admin')->first();

        return Schedule::query()
            ->with('periods')
            ->where('schedulable_type', User::class)
            ->where('schedulable_id', $dietitian?->id)
            ->where('schedule_type', ScheduleTypes::APPOINTMENT)
            ->orderBy('start_date', 'desc')
            ->get()
            ->map(function ($schedule) {
                $period = $schedule->periods->first();
                $metadata = $schedule->metadata ?? [];

                $statusLabel = match ($metadata['status'] ?? 'pending') {
                    'approved' => 'Onaylandı',
                    'pending' => 'Beklemede',
                    'rejected' => 'Reddedildi',
                    default => 'Beklemede',
                };

                return [
                    'Danışan' => $metadata['client_name'] ?? '-',
                    'E-Posta' => $metadata['client_email'] ?? '-',
                    'Telefon' => $metadata['client_phone'] ?? '-',
                    'Tarih' => Carbon::parse($schedule->start_date)->format('d.m.Y'),
                    'Başlangıç Saati' => $period ? Carbon::parse($period->start_time)->timezone('Europe/Istanbul')->format('H:i') : '-',
                    'Bitiş Saati' => $period ? Carbon::parse($period->end_time)->timezone('Europe/Istanbul')->format('H:i') : '-',
                    'Durum' => $statusLabel,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Danışan',
            'E-Posta',
            'Telefon',
            'Tarih',
            'Başlangıç Saati',
            'Bitiş Saati',
            'Durum',
        ];
    }
}
