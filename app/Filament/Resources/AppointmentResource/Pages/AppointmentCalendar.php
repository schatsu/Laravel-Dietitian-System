<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\Page;

class AppointmentCalendar extends Page
{
    protected static string $resource = AppointmentResource::class;

    protected static string $view = 'filament.resources.appointment-resource.pages.appointment-calendar';

    protected static ?string $title = 'Randevu Takvimi';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('list')
                ->label('Liste Görünümü')
                ->icon('heroicon-o-list-bullet')
                ->color('danger')
                ->url(static::getResource()::getUrl('index')),
        ];
    }

}
