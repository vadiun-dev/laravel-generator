<?php

namespace Hitocean\Generator\Facades;

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
