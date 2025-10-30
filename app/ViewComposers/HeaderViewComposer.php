<?php

namespace App\ViewComposers;

use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class HeaderViewComposer
{
    public function compose(View $view): void
    {
        $headerServices = Cache::rememberForever('header_services', function () {
            return \App\Models\Service::query()
                ->select([
                    'id','name', 'slug', 'status', 'description', 'order',
                    'image', 'seo_title', 'seo_description',
                ])
                ->where('status', true)
                ->orderBy('order')
                ->get();

        }) ?? collect();

        $view->with('headerServices', $headerServices);
    }
}
