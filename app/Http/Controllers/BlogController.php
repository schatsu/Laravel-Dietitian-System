<?php

namespace App\Http\Controllers;

use App\Enums\BlogCategoryStatusEnum;
use App\Enums\BlogStatusEnum;
use App\Helpers\LocalizedRouteHelper;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Category;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $categories = Category::query()
            ->select(['id', 'name', 'slug', 'status', 'description', 'order', 'parent_id'])
            ->where('status', true)
            ->whereHas('blogs', function ($q) {
                $q->where('status', BlogStatusEnum::ACTIVE);
            })
            ->orderBy('order')
            ->get();

        /** @var Blog $blogs */
        $blogs = Blog::query()
            ->select([
                'title', 'slug', 'status', 'description', 'order', 'category_id', 'is_featured',
                'content', 'seo_title', 'seo_description',
            ])
            ->with([
                'category' => fn($query) => $query->select(['id', 'name']),
                'media'
            ])
            ->where('status', BlogStatusEnum::ACTIVE)
            ->orderBy('order')
            ->paginate(8);

        return view('front.blogs.index', compact('blogs', 'categories'));
    }

    public function show(string $slug)
    {
        $categories = Category::query()
            ->select(['id', 'name', 'slug', 'status', 'description', 'order', 'parent_id'])
            ->where('status', true)
            ->whereHas('blogs', function ($q) {
                $q->where('status', BlogStatusEnum::ACTIVE);
            })
            ->orderBy('order')
            ->get();

        /** @var Blog $blog */
        $blog = Blog::query()
            ->where('slug', $slug)
            ->where('status', BlogStatusEnum::ACTIVE)
            ->with([
                'category',
                'tags',
                'media'
            ])
            ->firstOrFail();


        return view('front.blogs.show', compact('blog', 'categories'));
    }
}
