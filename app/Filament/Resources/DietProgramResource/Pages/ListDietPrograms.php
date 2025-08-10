<?php

namespace App\Filament\Resources\DietProgramResource\Pages;

use App\Enums\ClientStatusEnum;
use App\Enums\DietProgramStatusEnum;
use App\Enums\GenderEnum;
use App\Filament\Resources\ClientResource;
use App\Filament\Resources\DietProgramResource;
use App\Models\Client;
use App\Models\DietProgram;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;

class ListDietPrograms extends ListRecords
{
    protected static string $resource = DietProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('createClient')
                ->label('Diyet Programı Oluştur')
                ->modalHeading('Yeni Program Oluştur')
                ->modalSubmitActionLabel('Oluştur')
                ->form([
                    Section::make('Program Bilgileri')
                        ->schema([
                            Grid::make()
                                ->schema([
                                    Select::make('client_id')
                                        ->label('Danışan Seçiniz')
                                        ->relationship('client', 'first_name')
                                        ->getOptionLabelFromRecordUsing(fn($record) => $record->full_name)
                                        ->searchable()
                                        ->preload()
                                        ->required(),
                                    TextInput::make('name')
                                        ->label('Program Adı')
                                        ->required()
                                        ->maxLength(150),
                                    DatePicker::make('start_date')
                                        ->label('Başlangıç Tarihi')
                                        ->native(false)
                                        ->required(),
                                    DatePicker::make('target_date')
                                        ->label('Hedef Tarihi')
                                        ->native(false)
                                        ->required(),
                                    Select::make('status')
                                        ->label('Durum')
                                        ->options(DietProgramStatusEnum::labels())
                                        ->required(),
                                    Textarea::make('program_notes')
                                        ->label('Notlar')
                                        ->nullable()
                                        ->maxLength(500),
                                ])
                        ])
                ])
                ->action(function (array $data, Action $action) {
                    $program = DietProgram::query()->create($data);
                    $action->redirect(DietProgramResource::getUrl('program', ['record' => $program]));
                })
                ->icon('heroicon-o-table-cells')
                ->button(),
        ];
    }
}
