<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use App\Models\User;
use App\Services\BookAppointmentService;
use Carbon\Carbon;
use Exception;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateAppointment extends CreateRecord
{
    protected static string $resource = AppointmentResource::class;

    protected static ?string $title = 'Yeni Randevu';

    /**
     * @throws Exception
     */
    protected function handleRecordCreation(array $data): Model
    {
        $timeSlot = $data['time_slot'] ?? null;
        $appointmentDate = Carbon::parse($data['appointment_date'])->timezone('Europe/Istanbul')->format('Y-m-d');

        if (!$timeSlot) {
            throw new Exception('Randevu saati seçilmedi.');
        }

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
