<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DietitianScheduleResource\Pages;
use App\Models\User;
use App\Services\BookAppointmentService;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Zap\Models\Schedule;

class DietitianScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Müsaitlik Takvimi';
    protected static ?string $navigationGroup = 'Randevu Yönetimi';
    protected static ?int $navigationSort = 0;
    protected static ?string $pluralLabel = 'Müsaitlik Takvimleri';
    protected static ?string $label = 'Müsaitlik';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Takvim Bilgileri')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('Takvim Adı')
                                ->required()
                                ->placeholder('Örn: Çalışma Saatleri, Öğle Arası')
                                ->maxLength(255),

                            Select::make('schedule_type')
                                ->label('Takvim Türü')
                                ->options([
                                    'availability' => 'Müsaitlik (Çalışma Saatleri)',
                                    'blocked' => 'Bloklanmış (İzin/Tatil)',
                                ])
                                ->required()
                                ->default('availability')
                                ->reactive(),

                            DatePicker::make('start_date')
                                ->label('Başlangıç Tarihi')
                                ->required()
                                ->native(false)
                                ->default(now()),

                            DatePicker::make('end_date')
                                ->label('Bitiş Tarihi')
                                ->native(false)
                                ->default(now()->endOfYear()),

                            Toggle::make('is_recurring')
                                ->label('Tekrarlayan mı?')
                                ->default(true)
                                ->reactive(),

                            Select::make('frequency')
                                ->label('Tekrar Sıklığı')
                                ->options([
                                    'daily' => 'Günlük',
                                    'weekly' => 'Haftalık',
                                    'monthly' => 'Aylık',
                                ])
                                ->default('weekly')
                                ->visible(fn (callable $get) => $get('is_recurring')),
                        ]),
                    ]),

                Section::make('Seans Ayarları')
                    ->description('Randevu slotlarının süre ve ara ayarları')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('metadata.session_duration')
                                ->label('Seans Süresi')
                                ->options([
                                    15 => '15 dakika',
                                    20 => '20 dakika',
                                    30 => '30 dakika',
                                    45 => '45 dakika',
                                    60 => '60 dakika',
                                    90 => '90 dakika',
                                    120 => '120 dakika',
                                ])
                                ->default(45)
                                ->required()
                                ->helperText('Her randevunun süresi'),

                            Select::make('metadata.buffer_time')
                                ->label('Seans Arası Dinlenme')
                                ->options([
                                    0 => 'Dinlenme yok',
                                    5 => '5 dakika',
                                    10 => '10 dakika',
                                    15 => '15 dakika',
                                    20 => '20 dakika',
                                    30 => '30 dakika',
                                ])
                                ->default(15)
                                ->required()
                                ->helperText('Seanslar arası boşluk'),
                        ]),
                    ])
                    ->visible(fn (callable $get) => $get('schedule_type') === 'availability'),

                Section::make('Günler')
                    ->description('Hangi günlerde çalışılacak')
                    ->schema([
                        Select::make('frequency_config.days')
                            ->label('Hangi Günler')
                            ->multiple()
                            ->required()
                            ->options([
                                'monday' => 'Pazartesi',
                                'tuesday' => 'Salı',
                                'wednesday' => 'Çarşamba',
                                'thursday' => 'Perşembe',
                                'friday' => 'Cuma',
                                'saturday' => 'Cumartesi',
                                'sunday' => 'Pazar',
                            ])
                            ->default(['monday', 'tuesday', 'wednesday', 'thursday', 'friday']),
                    ]),

                Section::make('Zaman Dilimleri')
                    ->schema([
                        Repeater::make('periods_data')
                            ->label('Saat Aralıkları')
                            ->schema([
                                Grid::make(2)->schema([
                                    TimePicker::make('start_time')
                                        ->label('Başlangıç')
                                        ->seconds(false)
                                        ->native(false)
                                        ->required(),

                                    TimePicker::make('end_time')
                                        ->label('Bitiş')
                                        ->seconds(false)
                                        ->native(false)
                                        ->required(),
                                ]),
                            ])
                            ->defaultItems(1)
                            ->addActionLabel('Yeni Saat Dilimi Ekle')
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string =>
                                isset($state['start_time'], $state['end_time'])
                                    ? Carbon::parse($state['start_time'])->format('H:i') . ' - ' . Carbon::parse($state['end_time'])->format('H:i')
                                    : null
                            ),
                    ]),

                Section::make('Durum')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->heading('Müsaitlik Takvimleri')
            ->description('Diyetisyen çalışma saatleri ve izin günleri')
            ->modifyQueryUsing(function ($query) {
                // Sadece diyetisyenin schedule'larını göster
                $dietitian = User::role('super_admin')->first();
                if ($dietitian) {
                    $query->where('schedulable_type', User::class)
                          ->where('schedulable_id', $dietitian->id)
                          ->whereIn('schedule_type', [\Zap\Enums\ScheduleTypes::AVAILABILITY, \Zap\Enums\ScheduleTypes::BLOCKED]);
                }
            })
            ->columns([
                TextColumn::make('name')
                    ->label('Takvim Adı')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('schedule_type')
                    ->label('Tür')
                    ->badge()
                    ->formatStateUsing(function ($state): string {
                        $value = $state instanceof \Zap\Enums\ScheduleTypes ? $state->value : (string) $state;
                        return match ($value) {
                            'availability' => 'Müsaitlik',
                            'blocked' => 'Bloklanmış',
                            'appointment' => 'Randevu',
                            default => $value,
                        };
                    })
                    ->color(function ($state): string {
                        $value = $state instanceof \Zap\Enums\ScheduleTypes ? $state->value : (string) $state;
                        return match ($value) {
                            'availability' => 'success',
                            'blocked' => 'danger',
                            'appointment' => 'info',
                            default => 'gray',
                        };
                    }),

                TextColumn::make('start_date')
                    ->label('Başlangıç')
                    ->date('d.m.Y')
                    ->sortable(),

                TextColumn::make('end_date')
                    ->label('Bitiş')
                    ->date('d.m.Y')
                    ->sortable(),

                TextColumn::make('frequency')
                    ->label('Tekrar')
                    ->formatStateUsing(function ($state): string {
                        if ($state === null) {
                            return 'Tek Seferlik';
                        }
                        $value = $state instanceof \Zap\Enums\Frequency ? $state->value : (string) $state;
                        return match ($value) {
                            'daily' => 'Günlük',
                            'weekly' => 'Haftalık',
                            'monthly' => 'Aylık',
                            default => $value,
                        };
                    }),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('schedule_type')
                    ->label('Tür')
                    ->options([
                        'availability' => 'Müsaitlik',
                        'blocked' => 'Bloklanmış',
                    ]),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Aktif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDietitianSchedules::route('/'),
            'create' => Pages\CreateDietitianSchedule::route('/create'),
            'edit' => Pages\EditDietitianSchedule::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $dietitian = User::role('super_admin')->first();
        if (!$dietitian) {
            return null;
        }

        return (string) Schedule::where('schedulable_type', User::class)
            ->where('schedulable_id', $dietitian->id)
            ->whereIn('schedule_type', [\Zap\Enums\ScheduleTypes::AVAILABILITY, \Zap\Enums\ScheduleTypes::BLOCKED])
            ->count();
    }
}
