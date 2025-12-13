<?php

namespace App\Filament\Resources\DietitianScheduleResource\Pages;

use App\Filament\Resources\DietitianScheduleResource;
use App\Models\User;
use Carbon\Carbon;
use Filament\Resources\Pages\CreateRecord;
use Zap\Facades\Zap;

class CreateDietitianSchedule extends CreateRecord
{
    protected static string $resource = DietitianScheduleResource::class;

    protected static ?string $title = 'Yeni Takvim Oluştur';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Form verisini al ve Zap ile schedule oluştur
        return $data;
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $dietitian = User::role('super_admin')->first();

        $scheduleBuilder = Zap::for($dietitian)
            ->named($data['name']);

        // Schedule türüne göre builder'ı ayarla
        if ($data['schedule_type'] === 'availability') {
            $scheduleBuilder->availability();
        } else {
            $scheduleBuilder->blocked();
        }

        // Tarih aralığını ayarla
        $scheduleBuilder->from($data['start_date']);
        
        if (!empty($data['end_date'])) {
            $scheduleBuilder->to($data['end_date']);
        }

        // Frequency değerini string'e çevir (Enum olabilir)
        $frequencyValue = $data['frequency'] ?? 'weekly';
        if ($frequencyValue instanceof \BackedEnum) {
            $frequencyValue = $frequencyValue->value;
        } elseif (is_object($frequencyValue) && property_exists($frequencyValue, 'value')) {
            $frequencyValue = $frequencyValue->value;
        }

        // Tekrar ayarları - always include days for weekly recurring
        if ($data['is_recurring'] ?? false) {
            $days = $data['frequency_config']['days'] ?? ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
            $scheduleBuilder->weekly($days);
        }

        // Zaman dilimlerini ekle
        if (!empty($data['periods_data'])) {
            foreach ($data['periods_data'] as $period) {
                $startTime = Carbon::parse($period['start_time'])->format('H:i');
                $endTime = Carbon::parse($period['end_time'])->format('H:i');
                $scheduleBuilder->addPeriod($startTime, $endTime);
            }
        }

        // Seans ayarlarını metadata olarak ekle
        if ($data['schedule_type'] === 'availability') {
            $metadata = $data['metadata'] ?? [];
            $scheduleBuilder->withMetadata([
                'session_duration' => (int) ($metadata['session_duration'] ?? 45),
                'buffer_time' => (int) ($metadata['buffer_time'] ?? 15),
            ]);
        }

        // Schedule'ı kaydet ve döndür
        $schedule = $scheduleBuilder->save();

        // is_active false ise deaktif et
        if (!($data['is_active'] ?? true)) {
            $schedule->update(['is_active' => false]);
        }

        return $schedule;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
