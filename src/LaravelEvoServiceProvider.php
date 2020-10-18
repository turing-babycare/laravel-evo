<?php


namespace Turing\LaravelEvo;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\ServiceProvider;
use Turing\LaravelEvo\Exceptions\Handler;
use Turing\LaravelEvo\Microservice\Fetch;
use Turing\LaravelEvo\Microservice\Reply;
use \Turing\LaravelEvo\Facades\Reply as FacadesReply;

class LaravelEvoServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Reply::class, function () {
            return new Reply();
        });

        $this->app->singleton(Fetch::class, function () {
            return new Fetch(json_decode(config('evo.microservice_host'), true), FacadesReply::getTraceId());
        });

        $this->app->singleton(ExceptionHandler::class, Handler::class);
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__ . '/../config/evo.php' => config_path('evo.php')], 'laravel-evo-config');
        }
    }
}