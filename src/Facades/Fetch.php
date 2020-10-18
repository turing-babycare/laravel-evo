<?php


namespace Turing\LaravelEvo\Facades;


use Illuminate\Support\Facades\Facade;

class Fetch extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Turing\LaravelEvo\Microservice\Fetch::class;
    }
}