<?php

namespace Hitocean\Generator\Commands\Generators\Backend\Writers;

use Illuminate\Support\Str;

class Writer
{
    public static function rootFolder($rootFolder): string
    {
        return Str::ucfirst($rootFolder);
    }

    public static function rootNamespace($rootFolder): string
    {
        $rootFolder = static::rootFolder($rootFolder);

        return "Src\\$rootFolder";
    }

    public static function rootPath($rootFolder): string
    {
        $rootFolder = static::rootFolder($rootFolder);

        return "src/$rootFolder";
    }

    public static function import($rootFolder, $name): string
    {
        return 'use '. static::namespace($rootFolder, $name).';';
    }
}
