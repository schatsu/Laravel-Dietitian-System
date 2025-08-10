<?php

namespace App\Filament\Resources\DietProgramResource\Pages;

use App\Filament\Resources\DietProgramResource;
use App\Models\DietProgram;
use App\Models\Meal;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Collection;

class DietProgramEditor extends Page
{
    protected static string $resource = DietProgramResource::class;

    protected static string $view = 'filament.resources.diet-program-resource.pages.diet-program-editor';

    protected static ?string $title = 'Diyet Programı Oluştur';
}
