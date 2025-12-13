<?php

namespace App\Filament\Resources\DietitianScheduleResource\Pages;

use App\Filament\Resources\DietitianScheduleResource;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDietitianSchedule extends EditRecord
{
    protected static string $resource = DietitianScheduleResource::class;

    protected static ?string $title = 'Takvimi Düzenle';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Schedule'ın periods'larını form için hazırla
        $schedule = $this->record;
        $periods = $schedule->periods;

        $data['periods_data'] = $periods->map(function ($period) {
            return [
                'start_time' => $period->start_time,
                'end_time' => $period->end_time,
            ];
        })->toArray();

        // frequency_config'den days'i çıkar - null ise varsayılan günleri kullan
        $frequencyConfig = $schedule->frequency_config;
        if (is_array($frequencyConfig) && isset($frequencyConfig['days'])) {
            $data['frequency_config'] = ['days' => $frequencyConfig['days']];
        } else {
            // Varsayılan günler (hafta içi)
            $data['frequency_config'] = ['days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday']];
        }

        // Metadata'dan seans ayarlarını al
        $metadata = $schedule->metadata;
        if (is_array($metadata)) {
            $data['metadata'] = [
                'session_duration' => $metadata['session_duration'] ?? 45,
                'buffer_time' => $metadata['buffer_time'] ?? 15,
            ];
        } else {
            $data['metadata'] = [
                'session_duration' => 45,
                'buffer_time' => 15,
            ];
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $data;
    }

    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        // Metadata hazırla
        $metadata = [];
        if ($data['schedule_type'] === 'availability' && isset($data['metadata'])) {
            $metadata = [
                'session_duration' => (int) ($data['metadata']['session_duration'] ?? 45),
                'buffer_time' => (int) ($data['metadata']['buffer_time'] ?? 15),
            ];
        }

        // Frequency değerini string'e çevir (Enum olabilir)
        $frequencyValue = $data['frequency'] ?? null;
        if ($frequencyValue instanceof \BackedEnum) {
            $frequencyValue = $frequencyValue->value;
        } elseif (is_object($frequencyValue) && property_exists($frequencyValue, 'value')) {
            $frequencyValue = $frequencyValue->value;
        }
        
        // Frequency config hazırla - is_recurring true ve frequency weekly ise days'i kaydet
        $frequencyConfig = null;
        if ($data['is_recurring'] ?? false) {
            $days = $data['frequency_config']['days'] ?? [];
            if (!empty($days)) {
                $frequencyConfig = ['days' => $days];
            }
        }

        // Temel schedule bilgilerini güncelle
        $record->update([
            'name' => $data['name'],
            'schedule_type' => $data['schedule_type'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'] ?? null,
            'is_recurring' => $data['is_recurring'] ?? false,
            'frequency' => ($data['is_recurring'] ?? false) ? ($frequencyValue ?? 'weekly') : null,
            'frequency_config' => $frequencyConfig,
            'is_active' => $data['is_active'] ?? true,
            'metadata' => !empty($metadata) ? $metadata : null,
        ]);

        // Mevcut periods'ları sil ve yenilerini ekle
        $record->periods()->delete();

        if (!empty($data['periods_data'])) {
            foreach ($data['periods_data'] as $period) {
                $startTime = Carbon::parse($period['start_time'])->format('H:i:s');
                $endTime = Carbon::parse($period['end_time'])->format('H:i:s');

                $record->periods()->create([
                    'date' => $data['start_date'],
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'is_available' => $data['schedule_type'] === 'availability',
                ]);
            }
        }

        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
