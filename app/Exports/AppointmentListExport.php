<?php

namespace App\Exports;

use App\Models\Appointment;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AppointmentListExport implements FromCollection, WithHeadings
{
    /**
    * @return Collection
    */
    public function collection(): Collection
    {
        return Appointment::query()->with('slot')->get()->map(function ($appointment) {
            return [
                'Danışan' => $appointment->name,
                'E-Posta' => $appointment->email,
                'Telefon' => $appointment->phone,
                'Tarih' => $appointment->slot->date,
                'Başlangıç Saati' => $appointment->slot->start_time,
                'Bitiş Saati' => $appointment->slot->end_time,
                'Durum' => $appointment->status->label(),
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
