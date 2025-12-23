<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Zap\Models\Schedule;

class UpcomingAppointments extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'Yaklaşan Randevular';

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                $dietician = User::role('super_admin')->first();

                if (!$dietician) {
                    return Schedule::query()->whereNull('id');
                }

                return Schedule::query()
                    ->where('type', 'appointment')
                    ->whereDate('start_date', '>=', now())
                    ->orderBy('start_date')
                    ->orderBy('periods->0->start');
            })
            ->columns([

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Randevu Tarihi')
                    ->date('d F Y, l')
                    ->sortable(),

                Tables\Columns\TextColumn::make('periods')
                    ->label('Saat')
                    ->formatStateUsing(function ($state) {
                        $start = $state[0]['start'] ?? '-';
                        $end = $state[0]['end'] ?? '-';
                        return "{$start} - {$end}";
                    })
                    ->icon('heroicon-m-clock')
                    ->color('primary'),

                Tables\Columns\TextColumn::make('metadata.patient_name')
                    ->label('Hasta Adı')
                    ->searchable()
                    ->description(fn ($record) => $record->metadata['patient_phone'] ?? '')
                    ->default('İsimsiz Hasta'),

                Tables\Columns\TextColumn::make('metadata.notes')
                    ->label('Notlar')
                    ->limit(30)
                    ->tooltip(fn (Tables\Columns\TextColumn $column): ?string => $column->getState()),


                Tables\Columns\TextColumn::make('metadata.type')
                    ->label('Tür')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'online_consultation' => 'Online',
                        'face_to_face' => 'Yüz Yüze',
                        default => 'Genel',
                    })
                    ->color(fn ($state): string => match ($state) {
                        'online_consultation' => 'success',
                        'face_to_face' => 'warning',
                        default => 'gray',
                    }),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make()
                    ->label('İptal Et')
                    ->modalHeading('Randevuyu İptal Et')
                    ->modalDescription('Bu randevuyu silmek istediğinize emin misiniz? Bu işlem geri alınamaz ve slot tekrar boşa çıkar.')
                    ->after(function () {
                        \Filament\Notifications\Notification::make()
                            ->title('Randevu iptal edildi')
                            ->success()
                            ->send();
                    }),
            ])
            ->emptyStateHeading('Henüz randevu yok')
            ->emptyStateDescription('Hastalar randevu aldığında burada görünecektir.')
            ->poll('30s');
    }
}
