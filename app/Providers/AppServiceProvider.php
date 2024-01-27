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
            // TODO: Review after next KeyLighter release
            $keylighterVersion = KeyLighter::VERSION === '0.9-dev' ? '0.9.0' : KeyLighter::VERSION;

            $view->with('keylighterVersion', $keylighterVersion);
        });
    }

    public function register(): void
    {
        //
    }
}
