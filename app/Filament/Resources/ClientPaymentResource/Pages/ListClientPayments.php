<?php

namespace App\Filament\Resources\ClientPaymentResource\Pages;

use App\Filament\Resources\ClientPaymentResource;
use App\Filament\Resources\ClientPaymentResource\Widgets\ClientPaymentStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClientPayments extends ListRecords
{
    protected static string $resource = ClientPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ClientPaymentStats::class,
        ];
    }
}
