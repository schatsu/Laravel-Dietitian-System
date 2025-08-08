<?php

namespace App\Models;

use App\Enums\ClientStatusEnum;
use App\Enums\GenderEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Client extends Model
{
    use HasSlug;

    protected $fillable = [
        'first_name', 'last_name', 'email', 'slug',
        'phone', 'status', 'age', 'occupation', 'gender'
    ];

    protected function casts(): array
    {
        return [
            'gender' => GenderEnum::class,
            'status' => ClientStatusEnum::class,
            'age' => 'integer',
        ];
    }
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['first_name', 'last_name'])
            ->saveSlugsTo('slug');
    }
    public function physicalProfile(): HasOne
    {
        return $this->hasOne(ClientPhysicalProfile::class);
    }
    public function medicalProfile(): HasOne
    {
        return $this->hasOne(ClientMedicalProfile::class);
    }
    public function nutritionProfile(): HasOne
    {
        return $this->hasOne(ClientNutritionProfile::class);
    }
    public function lifestyleProfile(): HasOne
    {
        return $this->hasOne(ClientLifestyleProfile::class);
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            ClientStatusEnum::ACTIVE => 'success',
            ClientStatusEnum::PASSIVE => 'danger',
        };
    }

    public function getStatusTranslateNameAttribute(): string
    {
        return match ($this->status) {
            ClientStatusEnum::ACTIVE => 'Aktif',
            ClientStatusEnum::PASSIVE => 'Pasif',
        };
    }

    public function getFullNameAttribute(): string
    {
        if (is_null($this->first_name) || is_null($this->last_name)) {
            return '--';
        }
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getGenderFormattedAttribute(): string
    {
        return match ($this->gender) {
            GenderEnum::MALE => 'Erkek',
            GenderEnum::FEMALE => 'Kadın'
        };
    }
}
