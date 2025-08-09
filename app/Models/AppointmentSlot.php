<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppointmentSlot extends Model
{
    protected $fillable = ['weekly_schedule_id', 'date', 'start_time', 'end_time', 'is_booked', 'is_active'];

    public function appointment()
    {
        return $this->hasOne(Appointment::class)->withDefault([
            'name' => 'Müsait Slot'
        ]);
    }

    public function weeklySchedule(): BelongsTo
    {
        return $this->belongsTo(WeeklySchedule::class);
    }
}
