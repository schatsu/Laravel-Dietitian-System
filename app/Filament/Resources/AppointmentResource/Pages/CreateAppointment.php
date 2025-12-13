<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use App\Models\User;
use App\Services\BookAppointmentService;
use Carbon\Carbon;
use Filament\Resources\Pages\CreateRecord;

class CreateAppointment extends CreateRecord
{
    protected static string $resource = AppointmentResource::class;

    protected static ?string $title = 'Yeni Randevu';

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        // Zaman slotunu parse et
        $timeSlot = $data['time_slot'] ?? null;
        // native(false) ile gelen date'i Y-m-d formatına çevir
        $appointmentDate = Carbon::parse($data['appointment_date'])->format('Y-m-d');

        if (!$timeSlot) {
            throw new \Exception('Randevu saati seçilmedi.');
        }

        // Format: "09:00-10:00"
        [$startTime, $endTime] = explode('-', $timeSlot);

        $service = new BookAppointmentService();

        return $service->bookAppointment(
            $appointmentDate,
            trim($startTime),
            trim($endTime),
            [
                'name' => $data['metadata']['client_name'] ?? 'Bilinmeyen',
                'email' => $data['metadata']['client_email'] ?? null,
                'phone' => $data['metadata']['client_phone'] ?? null,
                'note' => $data['metadata']['note'] ?? null,
                'status' => $data['metadata']['status'] ?? 'pending',
            ]
        );
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
