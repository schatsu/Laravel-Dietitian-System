<?php

namespace App\Observers;

use App\Models\MealCategory;

class MealCategoryObserver
{
    public function created(MealCategory $mealCategory): void
    {
        self::renumberOrder();
    }

    public function updated(MealCategory $mealCategory): void
    {
        if ($mealCategory->isDirty('order')) {
            self::renumberOrder();
        }
    }

    public function deleted(MealCategory $mealCategory): void
    {
        self::renumberOrder();
    }
    public static function renumberOrder(): void
    {
        $allIds = MealCategory::query()->orderBy('order')->pluck('id');

        foreach ($allIds as $index => $id) {
            MealCategory::query()->where('id', $id)->update(['order' => $index + 1]);
        }
    }
}
