<?php

namespace App\Providers;

use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;
use Kadet\Highlighter\KeyLighter;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        ViewFacade::composer('layout', function (View $view) {
            $view->with('keylighterVersion', KeyLighter::VERSION);
        });
    }

    public function register(): void
    {
        //
    }
}
