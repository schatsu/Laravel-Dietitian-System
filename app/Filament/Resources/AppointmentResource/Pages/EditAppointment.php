<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAppointment extends EditRecord
{
    protected static string $resource = AppointmentResource::class;

    protected static ?string $title = 'Randevuyu Düzenle';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Schedule'dan form için veri hazırla
        $schedule = $this->record;
        $period = $schedule->periods->first();

        if ($period) {
            $startTime = Carbon::parse($period->start_time)->format('H:i');
            $endTime = Carbon::parse($period->end_time)->format('H:i');
            $data['time_slot'] = $startTime . '-' . $endTime;
            $data['appointment_date'] = $schedule->start_date;
        }

        // Metadata'yı form için hazırla
        $metadata = $schedule->metadata ?? [];
        $data['metadata'] = [
            'client_name' => $metadata['client_name'] ?? $schedule->name,
            'client_email' => $metadata['client_email'] ?? null,
            'client_phone' => $metadata['client_phone'] ?? null,
            'note' => $metadata['note'] ?? null,
            'status' => $metadata['status'] ?? 'pending',
        ];

        return $data;
    }

    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        // Zaman slotunu parse et
        $timeSlot = $data['time_slot'] ?? null;

        if ($timeSlot) {
            [$startTime, $endTime] = explode('-', $timeSlot);
            $startTime = Carbon::parse(trim($startTime))->format('H:i:s');
            $endTime = Carbon::parse(trim($endTime))->format('H:i:s');

            // Period'ları güncelle
            $record->periods()->delete();
            $record->periods()->create([
                'date' => $data['appointment_date'],
                'start_time' => $startTime,
                'end_time' => $endTime,
                'is_available' => false,
            ]);
        }

        // Schedule'ı güncelle
        $metadata = $data['metadata'] ?? [];
        $record->update([
            'name' => ($metadata['client_name'] ?? 'Randevu') . ' - Randevu',
            'start_date' => $data['appointment_date'],
            'metadata' => [
                'client_name' => $metadata['client_name'] ?? null,
                'client_email' => $metadata['client_email'] ?? null,
                'client_phone' => $metadata['client_phone'] ?? null,
                'note' => $metadata['note'] ?? null,
                'status' => $metadata['status'] ?? 'pending',
            ],
        ]);

        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
