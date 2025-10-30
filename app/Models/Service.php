<?php

namespace App\Models;

use App\Observers\ServiceObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\Conversions\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Tags\HasTags;

#[ObservedBy(ServiceObserver::class)]
class Service extends Model implements HasMedia
{
    use HasSlug, InteractsWithMedia, HasTags;

    protected $fillable = [
        'name', 'slug', 'status', 'description', 'content',
        'order', 'image', 'seo_title', 'seo_description',
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(fn($model) => $model?->slug ?? $model?->name ?? '')
            ->saveSlugsTo('slug');
    }

    protected function casts(): array
    {
        return [
            'order' => 'integer',
            'status' => 'boolean',
        ];
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Fit::Crop, 610, 710);

        $this->addMediaConversion('small')
            ->fit(Fit::Crop, 800, 590);
    }

    public function getShortDescriptionAttribute(): string
    {
       return Str::limit($this->description, 25);
    }
}
