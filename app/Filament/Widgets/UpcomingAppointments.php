<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Zap\Models\Schedule; // Zap'in modelini kullanıyoruz

class UpcomingAppointments extends BaseWidget
{
    protected static ?int $sort = 2; // Dashboard'daki sırası
    protected int | string | array $columnSpan = 'full'; // Tam genişlik
    protected static ?string $heading = 'Yaklaşan Randevular';

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                // ManageSchedule'daki mantığın aynısı: Yetkili kullanıcıyı bul
                $dietician = User::role('super_admin')->first();

                if (!$dietician) {
                    return Schedule::query()->whereNull('id'); // Boş döndür
                }

                return Schedule::query()
                    ->where('type', 'appointment') // Sadece randevular
                    ->whereDate('start_date', '>=', now()) // Geçmiş randevular gizlensin
                    ->orderBy('start_date')
                    ->orderBy('periods->0->start'); // Saate göre sırala
            })
            ->columns([
                // 1. Tarih Kolonu
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Randevu Tarihi')
                    ->date('d F Y, l') // Örn: 15 Ocak 2025, Çarşamba
                    ->sortable(),

                // 2. Saat Aralığı (JSON periods içinden okuma)
                Tables\Columns\TextColumn::make('periods')
                    ->label('Saat')
                    ->formatStateUsing(function ($state) {
                        // Zap veriyi [['start'=>'09:00', 'end'=>'09:45']] şeklinde tutar
                        $start = $state[0]['start'] ?? '-';
                        $end = $state[0]['end'] ?? '-';
                        return "{$start} - {$end}";
                    })
                    ->icon('heroicon-m-clock')
                    ->color('primary'),

                // 3. Hasta Adı (Metadata içinden okuma)
                Tables\Columns\TextColumn::make('metadata.patient_name')
                    ->label('Hasta Adı')
                    ->searchable() // İsimle arama yapılabilir
                    ->description(fn ($record) => $record->metadata['patient_phone'] ?? '') // Altına telefon no
                    ->default('İsimsiz Hasta'),

                // 4. Notlar
                Tables\Columns\TextColumn::make('metadata.notes')
                    ->label('Notlar')
                    ->limit(30)
                    ->tooltip(fn (Tables\Columns\TextColumn $column): ?string => $column->getState()),

                // 5. Durum (Metadata type)
                Tables\Columns\BadgeColumn::make('metadata.type')
                    ->label('Tür')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'online_consultation' => 'Online',
                        'face_to_face' => 'Yüz Yüze',
                        default => 'Genel',
                    })
                    ->colors([
                        'success' => 'online_consultation',
                        'warning' => 'face_to_face',
                    ]),
            ])
            ->actions([
                // Randevu İptal İşlemi
                Tables\Actions\DeleteAction::make()
                    ->label('İptal Et')
                    ->modalHeading('Randevuyu İptal Et')
                    ->modalDescription('Bu randevuyu silmek istediğinize emin misiniz? Bu işlem geri alınamaz ve slot tekrar boşa çıkar.')
                    ->after(function () {
                        // Silindikten sonra bildirim
                        \Filament\Notifications\Notification::make()
                            ->title('Randevu iptal edildi')
                            ->success()
                            ->send();
                    }),
            ])
            ->emptyStateHeading('Henüz randevu yok')
            ->emptyStateDescription('Hastalar randevu aldığında burada görünecektir.')
            ->poll('30s'); // Her 30 saniyede bir tabloyu yenile (yeni randevu gelirse diye)
    }
}
