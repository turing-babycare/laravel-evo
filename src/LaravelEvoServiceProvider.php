<?php


namespace Turing\LaravelEvo;

use Illuminate\Support\ServiceProvider;
use Turing\LaravelEvo\Microservice\Fetch;

class LaravelEvoServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Fetch::class, function () {
            return new Fetch([], '');
        });
    }
}