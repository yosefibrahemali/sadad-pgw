<?php

namespace YosefIb\SadadPGW;

use Illuminate\Support\ServiceProvider;

class SadadServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Sadad::class, function () {
            return new Sadad();
        });
    }

    public function boot()
    {

    }
}
