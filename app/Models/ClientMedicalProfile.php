<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientMedicalProfile extends Model
{
    protected $fillable = [
        'client_id', 'medical_conditions', 'medications',
        'allergies', 'food_allergies','additional_medical_notes'
    ];

    protected $casts = [
        'medical_conditions' => 'array',
        'allergies' => 'array',
        'food_allergies' => 'array',
        'medications' => 'array',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }


    public function getMedicationsFormattedAttribute(): string
    {
        return $this->formatRepeaterField($this->medications, 'medications');
    }

    public function getFoodAllergiesFormattedAttribute(): string
    {
        return $this->formatRepeaterField($this->food_allergies, 'food_allergies');
    }

    public function getAllergiesFormattedAttribute(): string
    {
        return $this->formatRepeaterField($this->allergies, 'allergies');
    }

    public function getMedicalConditionsFormattedAttribute(): string
    {
        return $this->formatRepeaterField($this->medical_conditions, 'medical_conditions');
    }
    protected function formatRepeaterField(?array $field, string $key): string
    {
        if (empty($field)) {
            return '--';
        }

        $items = array_map(fn($item) => $item[$key] ?? '', $field);

        return implode(', ', array_filter($items));
    }

}
