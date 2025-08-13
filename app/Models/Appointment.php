<?php

namespace App\Models;

use App\Enums\AppointmentStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    protected $fillable = [
        'appointment_slot_id',
        'name', 'email', 'phone', 'note',
        'status'
    ];

    protected function casts(): array
    {
        return [
            'status' => AppointmentStatusEnum::class,
        ];
    }

    public function slot(): BelongsTo
    {
        return $this->belongsTo(AppointmentSlot::class, 'appointment_slot_id');
    }
}
