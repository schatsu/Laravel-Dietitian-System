<?php

namespace App\Http\Controllers;

use App\Models\About;
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
}
