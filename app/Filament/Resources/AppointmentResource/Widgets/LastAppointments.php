<?php

namespace App\Filament\Resources\AppointmentResource\Widgets;

use App\Models\User;
use Carbon\Carbon;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Zap\Enums\ScheduleTypes;
use Zap\Models\Schedule;

class LastAppointments extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';
    protected static ?string $heading = 'Son 5 Randevu';

    public function table(Table $table): Table
    {
        $dietitian = User::role('super_admin')->first();

        return $table
            ->query(
                Schedule::query()
                    ->where('schedulable_type', User::class)
                    ->where('schedulable_id', $dietitian?->id)
                    ->where('schedule_type', ScheduleTypes::APPOINTMENT)
                    ->with('periods')
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('start_date')
                    ->label('Tarih')
                    ->date(format: 'd.m.Y')
                    ->sortable(),

                TextColumn::make('periods')
                    ->label('Saat')
                    ->formatStateUsing(function ($state, $record) {
                        $period = $record->periods->first();
                        if ($period) {
                            return Carbon::parse($period->start_time)->format('H:i') . ' - ' . Carbon::parse($period->end_time)->format('H:i');
                        }
                        return '-';
                    }),

                TextColumn::make('metadata.client_name')
                    ->label('Danışan')
                    ->default('-'),

                TextColumn::make('metadata.client_email')
                    ->label('E-posta')
                    ->default('-')
                    ->copyable(),

                TextColumn::make('metadata.client_phone')
                    ->label('Telefon')
                    ->default('-')
                    ->copyable(),

                TextColumn::make('metadata.status')
                    ->label('Durum')
                    ->badge()
                    ->formatStateUsing(fn($state) => match ($state) {
                        'approved' => 'Onaylandı',
                        'pending' => 'Beklemede',
                        'rejected' => 'Reddedildi',
                        default => $state ?? 'Beklemede',
                    })
                    ->color(fn($state) => match ($state) {
                        'approved' => 'success',
                        'pending' => 'warning',
                        'rejected' => 'danger',
                        default => 'warning',
                    }),
            ])
            ->paginated(false)
            ->emptyStateHeading('Henüz randevu bulunmuyor')
            ->emptyStateDescription('Yeni randevular oluşturuldukça burada görünecektir.')
            ->emptyStateIcon('heroicon-o-calendar');
    }
}
