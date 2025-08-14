<?php

namespace App\Filament\Resources\AppointmentResource\Widgets;

use App\Enums\AppointmentStatusEnum;
use App\Models\Appointment;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LastAppointments extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';
    protected static ?string $heading = 'Son 5 Randevu';
    public function table(Table $table): Table
    {
        return $table
            ->query(
               Appointment::query()
                ->with('slot')
                ->select(['appointment_slot_id','id', 'name', 'email', 'phone', 'status'])
                ->latest()
                ->limit(5)
            )
            ->columns([
                TextColumn::make('slot.date')
                    ->label('Tarih')
                    ->date(format: 'd.m.Y')
                    ->sortable(),

                TextColumn::make('slot.start_time')
                    ->label('Başlangıç Saati')
                    ->date(format: 'H:i')
                    ->sortable(),

                TextColumn::make('slot.end_time')
                    ->label('Bitiş Saati')
                    ->date(format: 'H:i')
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Danışan')
                    ->sortable(),

                TextColumn::make('email')
                    ->label('E-posta')
                    ->sortable()
                    ->copyable(),

                TextColumn::make('phone')
                    ->label('Telefon')
                    ->sortable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Durum')
                    ->formatStateUsing(fn(AppointmentStatusEnum $state) => $state->label())
                    ->badge()
                    ->color(fn(AppointmentStatusEnum $state) => $state->color()),
            ])->paginated(false);
    }
}
