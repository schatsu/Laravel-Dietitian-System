<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Cache::remember('services', 3600, function () {
            return Service::query()
                ->select([
                    'id','name', 'slug', 'status', 'description', 'order',
                    'image', 'seo_title', 'seo_description', 'content',
                ])
                ->where('status', true)
                ->with('media')
                ->orderBy('order')
                ->paginate(12);

        }) ?? collect();

        return view('front.services.index', compact('services'));
    }

    public function show(string $slug)
    {
        $service = Service::query()
            ->where('status', true)
            ->where('slug', $slug)
            ->firstOrFail();

        return view('front.services.show', compact('service'));
    }
}
