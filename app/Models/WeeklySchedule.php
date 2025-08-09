<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WeeklySchedule extends Model
{
    protected $fillable = ['day_of_week', 'start_time', 'end_time', 'duration', 'is_active'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean'
        ];
    }

    public function slots(): HasMany
    {
        return $this->hasMany(AppointmentSlot::class);
    }
}
