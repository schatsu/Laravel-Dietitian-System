<?php

namespace App\Filament\Resources\MealCategoryResource\Pages;

use App\Filament\Resources\MealCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMealCategories extends ListRecords
{
    protected static string $resource = MealCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
