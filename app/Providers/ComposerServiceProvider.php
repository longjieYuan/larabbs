<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Request $request)
    {
        // 在 footer 视图中绑定 location 数据
        view()->composer('layouts._footer', function($view) use ($request) {
            $location = geoip($request->ip());
            $view->with('location', $location->country.' - '.$location->state_name.' - '.$location->city);
        });
    }
}
