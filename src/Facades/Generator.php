<?php

namespace Hitocean\Generator\Commands\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Hitocean\Generator\Commands\Generator
 */
class Generator extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-generator';
    }
}
