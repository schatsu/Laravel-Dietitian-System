<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

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
        $schedule = $this->record;
        $period = $schedule->periods->first();

        if ($period) {
            $startTime = Carbon::parse($period->start_time)->timezone('Europe/Istanbul')->format('H:i');
            $endTime = Carbon::parse($period->end_time)->timezone('Europe/Istanbul')->format('H:i');
            $data['time_slot'] = $startTime . '-' . $endTime;
            $data['appointment_date'] = $schedule->start_date;
        }

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

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $timeSlot = $data['time_slot'] ?? null;

        if ($timeSlot) {
            [$startTime, $endTime] = explode('-', $timeSlot);
            $startTime = Carbon::parse(trim($startTime))->timezone('Europe/Istanbul')->format('H:i:s');
            $endTime = Carbon::parse(trim($endTime))->timezone('Europe/Istanbul')->format('H:i:s');

            $record->periods()->delete();
            $record->periods()->create([
                'date' => $data['appointment_date'],
                'start_time' => $startTime,
                'end_time' => $endTime,
                'is_available' => false,
            ]);
        }

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
