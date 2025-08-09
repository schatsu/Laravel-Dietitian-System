<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use App\Models\AppointmentSlot;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAppointment extends EditRecord
{
    protected static string $resource = AppointmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        unset($data['slot_date']);

        return $data;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (isset($data['appointment_slot_id'])) {
            $slot = AppointmentSlot::query()->find($data['appointment_slot_id']);
            if ($slot) {
                $data['slot_date'] = $slot->date;
            }
        }

        return $data;
    }

    protected function beforeSave(): void
    {
        if ($this->record->appointment_slot_id !== $this->data['appointment_slot_id']) {
            $oldSlot = $this->record->slot;
            if ($oldSlot) {
                $oldSlot->is_booked = false;
                $oldSlot->save();
            }
        }
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
