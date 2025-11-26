<?php

namespace App\View\Components;

use App\Enums\BlogStatusEnum;
use App\Models\Blog;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class FeaturedBlog extends Component
{
    /**
     * Create a new component instance.
     */
    public Collection $featuredBlogs;
    public function __construct()
    {
        $this->featuredBlogs = Cache::remember('featured_blogs', 3600, function () {
            return Blog::query()
                ->with(['category', 'media'])
                ->where('status', BlogStatusEnum::ACTIVE)
                ->where('is_featured', true)
                ->orderBy('order')
                ->limit(3)
                ->get();
        });
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.featured-blog', [
            'featuredBlogs' => $this->featuredBlogs,
        ]);
    }
}
