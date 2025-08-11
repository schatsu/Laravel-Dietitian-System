<?php

namespace App\Filament\Resources\MealCategoryResource\Pages;

use App\Filament\Resources\MealCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMealCategory extends EditRecord
{
    protected static string $resource = MealCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
