<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentResource\Pages;
use App\Filament\Resources\AppointmentResource\RelationManagers;
use App\Filament\Resources\AppointmentResource\Widgets\CalendarWidget;
use App\Models\Appointment;
use App\Models\AppointmentSlot;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';
    protected static ?string $navigationLabel = 'Randevular';
    protected static ?string $navigationGroup = 'Randevu Yönetimi';
    protected static ?int $navigationSort = 2;
    protected static ?string $pluralLabel = 'Randevular';
    protected static ?string $label = 'Randevu';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Randevu Bilgileri')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('slot_date')
                                ->label('Randevu Tarihi')
                                ->required()
                                ->reactive()
                                ->searchable()
                                ->preload()
                                ->options(function () {
                                    return AppointmentSlot::query()
                                        ->where('is_active', true)
                                        ->where('is_booked', false)
                                        ->orderBy('date')
                                        ->pluck('date', 'date')
                                        ->unique();
                                }),

                            Select::make('appointment_slot_id')
                            ->label('Randevu Saati')
                                ->required()
                                ->searchable()
                                ->preload()
                                ->disabled(fn (callable $get) => !$get('slot_date'))
                                ->options(function (callable $get) {
                                    $date = $get('slot_date');
                                    if (!$date) {
                                        return [];
                                    }

                                    return AppointmentSlot::query()
                                        ->where('date', $date)
                                        ->where('is_active', true)
                                        ->where('is_booked', false)
                                        ->orderBy('start_time')
                                        ->get()
                                        ->mapWithKeys(fn ($slot) => [
                                            $slot->id => $slot->start_time . ' - ' . $slot->end_time,
                                        ]);
                                }),

                            TextInput::make('name')->label('Danışan Adı')->required()->maxLength(255),

                            TextInput::make('email')->label('E-posta')->email()->prefix('@')->maxLength(255),

                            TextInput::make('phone')->label('Telefon')->prefix('+90')->mask('(999) 999 99 99')->maxLength(50),

                            Textarea::make('note')->label('Not')->maxLength(1000),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('slot.date')
                    ->label('Tarih')
                    ->date()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('slot.start_time')
                    ->label('Başlangıç Saati')
                    ->sortable(),

                TextColumn::make('slot.end_time')
                    ->label('Bitiş Saati')
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Danışan')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('email')
                    ->label('E-posta')
                    ->sortable()
                    ->copyable()
                    ->searchable(),

                TextColumn::make('phone')
                    ->label('Telefon')
                    ->sortable()
                    ->copyable()
                    ->searchable(),

                TextColumn::make('note')
                    ->label('Not')
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->note)
                    ->toggleable(isToggledHiddenByDefault: true)
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
            'calendar' => Pages\AppointmentCalendar::route('/calendar'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
