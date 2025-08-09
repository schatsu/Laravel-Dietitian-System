<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WeeklyScheduleResource\Pages;
use App\Filament\Resources\WeeklyScheduleResource\RelationManagers;
use App\Models\WeeklySchedule;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;

class WeeklyScheduleResource extends Resource
{
    protected static ?string $model = WeeklySchedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'Randevu Yönetimi';

    protected static ?string $navigationLabel = 'Haftalık Program';

    protected static ?string $pluralLabel = 'Haftalık Programlar';
    protected static ?string $label = 'Program';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                ->schema([
                   Forms\Components\Grid::make()
                   ->schema([
                       Select::make('day_of_week')
                           ->label('Hafta Günü')
                           ->options([
                               1 => 'Pazartesi',
                               2 => 'Salı',
                               3 => 'Çarşamba',
                               4 => 'Perşembe',
                               5 => 'Cuma',
                               6 => 'Cumartesi',
                               7 => 'Pazar',
                           ])
                           ->required(),

                       TimePicker::make('start_time')
                           ->label('Başlangıç Saati')
                           ->seconds(false)
                           ->native(false)
                           ->required(),

                       TimePicker::make('end_time')
                           ->label('Bitiş Saati')
                           ->seconds(false)
                           ->native(false)
                           ->required(),

                       Select::make('duration')
                           ->label('Randevu Süresi (Dakika)')
                           ->options([
                               15 => '15 dakika',
                               20 => '20 dakika',
                               30 => '30 dakika',
                               45 => '45 dakika',
                               60 => '60 dakika',
                           ])
                           ->default(30)
                           ->required(),

                       Toggle::make('is_active')
                           ->label('Aktif mi?')
                           ->default(true),
                   ])
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('day_of_week')
                    ->label('Hafta Günü')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        0 => 'Pazar',
                        1 => 'Pazartesi',
                        2 => 'Salı',
                        3 => 'Çarşamba',
                        4 => 'Perşembe',
                        5 => 'Cuma',
                        6 => 'Cumartesi',
                        default => 'Bilinmiyor',
                    })
                    ->sortable(),

                TextColumn::make('start_time')
                    ->label('Başlangıç Saati')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => Carbon::parse($state)->format('H:i')),

                TextColumn::make('end_time')
                    ->label('Bitiş Saati')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => Carbon::parse($state)->format('H:i')),

                TextColumn::make('duration')
                    ->label('Süre (dk)')
                    ->sortable(),

                TextColumn::make('is_active')
                    ->label('Aktif mi?')
                    ->badge()
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Aktif' : 'Pasif')
                    ->color(fn (bool $state): string => $state ? 'success' : 'danger')
                    ->sortable()

        ])
            ->defaultSort('day_of_week')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\SlotsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWeeklySchedules::route('/'),
            'create' => Pages\CreateWeeklySchedule::route('/create'),
            'edit' => Pages\EditWeeklySchedule::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
