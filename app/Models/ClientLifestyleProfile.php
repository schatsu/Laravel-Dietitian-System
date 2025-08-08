<?php

namespace App\Models;

use App\Enums\ClientActivityLevelEnum;
use App\Enums\ClientAlcoholConsumptionEnum;
use App\Enums\ClientSmokingStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientLifestyleProfile extends Model
{
    protected $fillable = [
        'counsellor_id', 'activity_level', 'sleep_hours',
        'water_intake', 'smoking_status', 'alcohol_consumption',
        'extra_notes'
    ];

    protected function casts(): array
    {
        return [
            'activity_level' => ClientActivityLevelEnum::class,
            'smoking_status' => ClientSmokingStatusEnum::class,
            'alcohol_consumption' => ClientAlcoholConsumptionEnum::class,
        ];
    }

    public function getActivityLevelFormattedAttribute(): string
    {
        if (is_null($this->activity_level))
        {
            return '--';
        }
        return match ($this->activity_level)
        {
            ClientActivityLevelEnum::SEDENTARY => 'Hareketsiz (Masabaşı işi)',
            ClientActivityLevelEnum::LIGHT => 'Az Aktif (Haftada 1-3 gün egzersiz)',
            ClientActivityLevelEnum::MODERATE => 'Orta Aktif (Haftada 3-5 gün egzersiz)',
            ClientActivityLevelEnum::VERY_ACTIVE => 'Çok Aktif (Haftada 6-7 gün egzersiz)',
            ClientActivityLevelEnum::EXTREMELY_ACTIVE => 'Son derece Aktif (Günde 2 kez egzersiz)',
        };
    }
    public function getSmokingStatusFormattedAttribute(): string
    {
        if (is_null($this->smoking_status))
        {
            return '--';
        }
        return match ($this->smoking_status)
        {
            ClientSmokingStatusEnum::NON_SMOKER => 'Sigara kullanmıyor',
            ClientSmokingStatusEnum::OCCASIONAL => 'Ara sıra',
            ClientSmokingStatusEnum::REGULAR => 'Düzenli',
        };
    }
    public function getAlcoholConsumptionFormattedAttribute(): string
    {
        if (is_null($this->alcohol_consumption))
        {
            return '--';
        }
        return match ($this->alcohol_consumption)
        {
            ClientAlcoholConsumptionEnum::NONE => 'Alkol kullanmıyor',
            ClientAlcoholConsumptionEnum::OCCASIONAL => 'Ara sıra',
            ClientAlcoholConsumptionEnum::REGULAR => 'Düzenli',
        };
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
