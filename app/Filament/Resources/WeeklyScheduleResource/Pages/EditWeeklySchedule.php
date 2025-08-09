<?php

namespace App\Filament\Resources\WeeklyScheduleResource\Pages;

use App\Filament\Resources\WeeklyScheduleResource;
use App\Helpers\AppointmentHelper;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWeeklySchedule extends EditRecord
{
    protected static string $resource = WeeklyScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }


    public function afterSave(): void
    {
        AppointmentHelper::generateSlots();

        $this->dispatch('updateSlots');
    }

}
