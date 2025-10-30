<?php

namespace App\ViewComposers;

use App\Models\Page;
use App\Settings\SiteSettings;
use App\Settings\SocialSettings;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class SettingsViewComposer
{
    public function compose(View $view): void
    {
        $generalSetting = app(SiteSettings::class) ?? [];
        $socialMediaSetting = app(SocialSettings::class) ?? [];

        $pages = Cache::remember('pages', 60 * 60 * 24, function () {
            return Page::query()
                ->select(['id', 'slug', 'title'])
                ->where('status', true)
                ->orderBy('order')
                ->get();
        });

        $view->with('generalSetting', $generalSetting);
        $view->with('socialMediaSetting', $socialMediaSetting);
        $view->with('pages', $pages);
    }
}
