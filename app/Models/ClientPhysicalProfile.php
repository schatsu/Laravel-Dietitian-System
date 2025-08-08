<?php

namespace App\Models;

use App\Enums\GoalTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientPhysicalProfile extends Model
{
    protected $fillable = [
        'client_name', 'initial_weight', 'initial_height',
        'target_weight', 'goal_type'
    ];

    protected function casts():array
    {
        return [
            'goal_type' => GoalTypeEnum::class,
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function getGoalTypeFormattedAttribute(): string
    {
        return match ($this->goal_type)
        {
            GoalTypeEnum::WEIGHT_LOSS => 'Kilo Verme',
            GoalTypeEnum::WEIGHT_GAIN => 'Kilo Alma',
            GoalTypeEnum::WEIGHT_MAINTENANCE => 'Kilo Koruma',
            GoalTypeEnum::MUSCLE_GAIN => 'Kas Kazanma',
            GoalTypeEnum::HEALTHY_NUTRITION => 'Sağlıklı Beslenme',
            GoalTypeEnum::BODY_SHAPING => 'Vücut Şekillendirme',
        };
    }
}
