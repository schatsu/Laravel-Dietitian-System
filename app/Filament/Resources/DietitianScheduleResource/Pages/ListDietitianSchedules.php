<?php

namespace App\Filament\Resources\DietitianScheduleResource\Pages;

use App\Filament\Resources\DietitianScheduleResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;

class ListDietitianSchedules extends ListRecords
{
    protected static string $resource = DietitianScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Yeni Takvim'),
        ];
    }
}
