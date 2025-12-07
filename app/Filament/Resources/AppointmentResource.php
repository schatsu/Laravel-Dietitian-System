<?php

namespace App\Filament\Resources;

use App\Enums\AppointmentStatusEnum;
use App\Exports\AppointmentListExport;
use App\Exports\ClientPaymentsExport;
use App\Filament\Resources\AppointmentResource\Pages;
use App\Filament\Resources\AppointmentResource\RelationManagers;
use App\Models\Appointment;
use App\Models\AppointmentSlot;
use Exception;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Maatwebsite\Excel\Facades\Excel;


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
                            Select::make('status')->label('Durum')
                                ->options(AppointmentStatusEnum::options()),

                            Textarea::make('note')->label('Not')
                                ->columnSpanFull()
                                ->maxLength(1000),
                        ]),
                    ]),
            ]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->heading('Randevular')
            ->description('Randevularınızı buradan görüntüleyebilirsiniz.')
            ->headerActions([
                Action::make('export_excel')
                    ->label('Excel İndir')
                    ->icon('heroicon-o-document')
                    ->action(fn() => Excel::download(new AppointmentListExport, 'randevular.xlsx')),
            ])
            ->columns([
                TextColumn::make('slot.date')
                    ->label('Tarih')
                    ->date()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('slot.start_time')
                    ->label('Başlangıç Saati')
                    ->dateTime('H:i')
                    ->sortable(),

                TextColumn::make('slot.end_time')
                    ->label('Bitiş Saati')
                    ->dateTime('H:i')
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
                Tables\Columns\TextColumn::make('status')
                    ->label('Durum')
                    ->formatStateUsing(fn(AppointmentStatusEnum $state) => $state->label())
                    ->badge()
                    ->color(fn(AppointmentStatusEnum $state) => $state->color()),

                TextColumn::make('note')
                    ->label('Not')
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->note)
                    ->toggleable(isToggledHiddenByDefault: true)
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                ->label('Durum')
                ->options(AppointmentStatusEnum::options()),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])->defaultSort('created_at', 'desc');
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
