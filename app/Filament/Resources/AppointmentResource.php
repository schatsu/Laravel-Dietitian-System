<?php

namespace App\Filament\Resources;

use App\Enums\AppointmentStatusEnum;
use App\Exports\AppointmentListExport;
use App\Filament\Resources\AppointmentResource\Pages;
use App\Models\User;
use App\Services\BookAppointmentService;
use Carbon\Carbon;
use Exception;
use Filament\Forms\Components\DatePicker;
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
use Zap\Facades\Zap;
use Zap\Models\Schedule;

class AppointmentResource extends Resource
{
    protected static ?string $model = Schedule::class;

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
                            DatePicker::make('appointment_date')
                                ->label('Randevu Tarihi')
                                ->required()
                                ->reactive()
                                ->native(false)
                                ->placeholder('Randevu Tarihi Seçiniz')
                                ->minDate(today()->timezone('Europe/Istanbul'))
                                ->afterStateUpdated(fn (callable $set) => $set('time_slot', null)),

                            Select::make('time_slot')
                                ->label('Randevu Saati')
                                ->required()
                                ->searchable()
                                ->reactive()
                                ->native(false)
                                ->placeholder('Randevu Saati Seçiniz')
                                ->disabled(fn (callable $get) => !$get('appointment_date'))
                                ->options(function (callable $get) {
                                    $date = $get('appointment_date');
                                    if (!$date) {
                                        return [];
                                    }
                                    $formattedDate = Carbon::parse($date)->timezone('Europe/Istanbul')->format('Y-m-d');
                                    $service = new BookAppointmentService();
                                    $slots = $service->getAvailableSlots($formattedDate);

                                    return collect($slots)
                                        ->sortBy('start_time')
                                        ->filter(fn ($slot) => $slot['is_available'] ?? false)
                                        ->mapWithKeys(fn ($slot) => [
                                            $slot['start_time'] . '-' . $slot['end_time'] =>
                                                $slot['start_time'] . ' - ' . $slot['end_time'],
                                        ])
                                        ->toArray();
                                })
                                ->disableOptionWhen(function (string $value, callable $get) {
                                    $date = $get('appointment_date');
                                    if (!$date) return false;

                                    $istanbulNow = now('Europe/Istanbul');
                                    $formattedDate = Carbon::parse($date)->timezone('Europe/Istanbul')->format('Y-m-d');

                                    if ($formattedDate === $istanbulNow->format('Y-m-d')) {
                                        $startTime = str($value)->before('-')->toString();

                                        return $startTime < $istanbulNow->format('H:i');
                                    }

                                    return false;
                                })
                                ->helperText(fn (callable $get) =>
                                $get('appointment_date')
                                    ? 'Geçmiş saatler otomatik olarak devre dışı bırakılır.'
                                    : 'Önce tarih seçin'
                                ),

                            TextInput::make('metadata.client_name')
                                ->label('Danışan Adı')
                                ->required()
                                ->maxLength(255),

                            TextInput::make('metadata.client_email')
                                ->label('E-posta')
                                ->email()
                                ->prefix('@')
                                ->maxLength(255),

                            TextInput::make('metadata.client_phone')
                                ->label('Telefon')
                                ->prefix('+90')
                                ->mask('(999) 999 99 99')
                                ->maxLength(50),

                            Select::make('metadata.status')
                                ->label('Durum')
                                ->options(AppointmentStatusEnum::options())
                                ->default('pending'),

                            Textarea::make('metadata.note')
                                ->label('Not')
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
            ->description('Tüm randevularınızı buradan görüntüleyebilirsiniz.')
            ->modifyQueryUsing(function ($query) {
                $dietitian = User::role('super_admin')->first();
                if ($dietitian) {
                    $query->where('schedulable_type', User::class)
                          ->where('schedulable_id', $dietitian->id)
                          ->where('schedule_type', \Zap\Enums\ScheduleTypes::APPOINTMENT);
                }
            })
            ->headerActions([
                Action::make('export_excel')
                    ->label('Excel İndir')
                    ->icon('heroicon-o-document')
                    ->action(fn() => Excel::download(new AppointmentListExport, 'randevular.xlsx')),
            ])
            ->columns([
                TextColumn::make('start_date')
                    ->label('Tarih')
                    ->date('d.m.Y')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('periods')
                    ->label('Saat')
                    ->formatStateUsing(function ($record) {
                        $period = $record->periods->first();
                        if (!$period) return '-';
                        return Carbon::parse($period->start_time)->timezone('Europe/Istanbul')->format('H:i') .
                               ' - ' .
                               Carbon::parse($period->end_time)->timezone('Europe/Istanbul')->format('H:i');
                    }),

                TextColumn::make('metadata.client_name')
                    ->label('Danışan')
                    ->sortable()
                    ->searchable(query: function ($query, $search) {
                        $query->where('metadata->client_name', 'like', "%{$search}%");
                    }),

                TextColumn::make('metadata.client_email')
                    ->label('E-posta')
                    ->copyable()
                    ->toggleable(),

                TextColumn::make('metadata.client_phone')
                    ->label('Telefon')
                    ->copyable()
                    ->toggleable(),

                TextColumn::make('metadata.status')
                    ->label('Durum')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'approved' => 'Onaylandı',
                        'pending' => 'Beklemede',
                        'rejected' => 'Reddedildi',
                        default => $state ?? 'Beklemede',
                    })
                    ->color(fn (?string $state): string => match ($state) {
                        'approved' => 'success',
                        'pending' => 'warning',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('metadata.note')
                    ->label('Not')
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->metadata['note'] ?? null)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Durum')
                    ->options(AppointmentStatusEnum::options())
                    ->query(function ($query, $data) {
                        if ($data['value']) {
                            $query->where('metadata->status', $data['value']);
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('approve')
                        ->label('Onayla')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($record) {
                            $metadata = $record->metadata ?? [];
                            $metadata['status'] = 'approved';
                            $record->update(['metadata' => $metadata]);
                        })
                        ->visible(fn ($record) => ($record->metadata['status'] ?? 'pending') !== 'approved'),

                    Tables\Actions\Action::make('reject')
                        ->label('Reddet')
                        ->icon('heroicon-o-x-mark')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function ($record) {
                            $metadata = $record->metadata ?? [];
                            $metadata['status'] = 'rejected';
                            $record->update(['metadata' => $metadata]);
                        })
                        ->visible(fn ($record) => ($record->metadata['status'] ?? 'pending') !== 'rejected'),

                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('start_date', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
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
        $dietitian = User::role('super_admin')->first();
        if (!$dietitian) {
            return null;
        }

        return (string) Schedule::query()->where('schedulable_type', User::class)
            ->where('schedulable_id', $dietitian->id)
            ->where('schedule_type', \Zap\Enums\ScheduleTypes::APPOINTMENT)
            ->count();
    }
}
