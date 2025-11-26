<?php

namespace App\Http\Controllers;

use App\Models\About;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        $about = About::query()
            ->select(['id','title','slug', 'content','highlights'])
            ->first();

        return view('front.about', compact('about'));
    }

    public function contact()
    {
        return view('front.contact');
    }

    public function show(string $slug)
    {
        $page = Page::query()
            ->where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();

        return view('app.pages.page', compact('page'));
    }
}
