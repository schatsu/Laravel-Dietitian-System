<?php

namespace App\Models;

use App\Enums\MealTimeEnum;
use App\Enums\MealUnitEnum;
use App\Enums\ProgramDayEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DietProgramItem extends Model
{
    protected $fillable = [
        'diet_program_id',
        'meal_id',
        'day',
        'meal_time',
        'quantity',
        'unit',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'day' => ProgramDayEnum::class,
            'meal_time' => MealTimeEnum::class,
        ];
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(DietProgram::class);
    }

    public function meal(): BelongsTo
    {
        return $this->belongsTo(Meal::class);
    }

    public function getUnitLabelAttribute(): string
    {
        return MealUnitEnum::from($this->unit)->label();
    }

}
