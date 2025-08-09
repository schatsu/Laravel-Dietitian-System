<?php

namespace App\Filament\Resources\WeeklyScheduleResource\Pages;

use App\Filament\Resources\WeeklyScheduleResource;
use App\Helpers\AppointmentHelper;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateWeeklySchedule extends CreateRecord
{
    protected static string $resource = WeeklyScheduleResource::class;

    protected function afterCreate(): void
    {
        AppointmentHelper::generateSlots();
    }
}
