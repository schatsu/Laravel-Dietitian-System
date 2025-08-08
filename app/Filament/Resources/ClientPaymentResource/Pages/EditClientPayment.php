<?php

namespace App\Filament\Resources\ClientPaymentResource\Pages;

use App\Filament\Resources\ClientPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClientPayment extends EditRecord
{
    protected static string $resource = ClientPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
