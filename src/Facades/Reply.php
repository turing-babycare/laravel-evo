<?php


namespace Turing\LaravelEvo\Facades;


use Illuminate\Support\Facades\Facade;

class Reply extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Turing\LaravelEvo\Microservice\Reply::class;
    }
}