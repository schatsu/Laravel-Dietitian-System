<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAppointment extends CreateRecord
{
    protected static string $resource = AppointmentResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        unset($data['slot_date']);

        return $data;
    }


    protected function afterSave(): void
    {
        $slot = $this->record->slot;
        if ($slot && !$slot->is_booked) {
            $slot->is_booked = true;
            $slot->save();
        }
    }
}
