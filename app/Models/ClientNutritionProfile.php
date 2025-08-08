<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientNutritionProfile extends Model
{
    protected $fillable = [
        'client_id', 'favorite_foods', 'disliked_foods',
        'dietary_restrictions', 'meal_frequency'
    ];

    protected $casts = [
        'favorite_foods' => 'array',
        'disliked_foods' => 'array',
        'dietary_restrictions' => 'array',
    ];

    public function getFavoriteFoodsFormattedAttribute(): string
    {
        return $this->formatRepeaterField($this->favorite_foods, 'favorite_foods');
    }

    public function getDislikedFoodsFormattedAttribute(): string
    {
        return $this->formatRepeaterField($this->disliked_foods, 'disliked_foods');
    }

    public function getDietaryRestrictionsFormattedAttribute(): string
    {
        return $this->formatRepeaterField($this->dietary_restrictions, 'dietary_restrictions');
    }
    protected function formatRepeaterField(?array $field, string $key): string
    {
        if (empty($field)) {
            return '--';
        }

        $items = array_map(fn($item) => $item[$key] ?? '', $field);

        return implode(', ', array_filter($items));
    }


    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
