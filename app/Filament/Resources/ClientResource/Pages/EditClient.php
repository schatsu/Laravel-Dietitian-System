<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Resources\ClientResource;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditClient extends EditRecord
{
    protected static string $resource = ClientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function resolveRecord(int|string $key): Model
    {
        return static::getModel()::with(['physicalProfile', 'medicalProfile', 'nutritionProfile', 'lifestyleProfile'])->findOrFail($key);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $physicalProfile = $data['physicalProfile'] ?? [];
        unset($data['physicalProfile']);

        $this->record->physicalProfile()->updateOrCreate([
            'client_id' => $this->record->id,
        ], $physicalProfile);

        $medicalProfile = $data['medicalProfile'] ?? [];
        unset($data['medicalProfile']);

        $this->record->medicalProfile()->updateOrCreate([
            'client_id' => $this->record->id,
        ], $medicalProfile);

        $nutritionProfile = $data['nutritionProfile'] ?? [];
        unset($data['nutritionProfile']);

        $this->record->nutritionProfile()->updateOrCreate([
            'client_id' => $this->record->id,
        ], $nutritionProfile);

        $lifestyleProfile = $data['lifestyleProfile'] ?? [];
        unset($data['lifestyleProfile']);

        $this->record->lifestyleProfile()->updateOrCreate([
            'client_id' => $this->record->id,
        ], $lifestyleProfile);

        return $data;
    }
}
