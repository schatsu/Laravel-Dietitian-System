<?php

namespace App\Models;

use App\Observers\FaqObserver;
use App\Observers\MealCategoryObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
#[ObservedBy(MealCategoryObserver::class)]
class MealCategory extends Model implements Sortable
{
    use SortableTrait;

    protected $fillable = [
        'name','order','is_popular', 'status'
    ];

    protected function casts(): array
    {
        return [
            'is_popular' => 'boolean',
            'order' => 'integer',
            'status' => 'boolean',
        ];
    }

    public $sortable = [
        'order_column_name' => 'order',
        'sort_when_creating' => true,
    ];

    public function meals(): HasMany
    {
        return $this->hasMany(Meal::class);
    }
}
