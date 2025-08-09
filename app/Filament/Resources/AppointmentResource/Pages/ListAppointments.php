<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use App\Filament\Resources\AppointmentResource\Widgets\CalendarWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAppointments extends ListRecords
{
    protected static string $resource = AppointmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('calendar')
                ->label('Takvim Görünümü')
                ->icon('heroicon-o-calendar')
                ->color('danger')
                ->url(static::getResource()::getUrl('calendar')),
        ];
    }
}
