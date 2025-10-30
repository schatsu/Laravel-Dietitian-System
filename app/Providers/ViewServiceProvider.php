<?php

namespace App\Providers;

use App\ViewComposers\HeaderViewComposer;
use App\ViewComposers\SettingsViewComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot():void
    {
        View::composer('*', HeaderViewComposer::class);
        View::composer('*', SettingsViewComposer::class);
    }
}
