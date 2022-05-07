<?php

namespace Hitocean\Generator\Commands\Generators\Backend\Writers;

abstract class ClassWriter extends Writer
{
    abstract public static function className($name): string;

    public static function namespace($rootFolder, $name): string
    {
        $root = self::rootNamespace($rootFolder);
        $folder = static::$folder;

        return "$root\\$folder\\".static::className($name);
    }

    public static function path($rootFolder, $name): string
    {
        $folder = static::$folder;
        $root = self::rootPath($rootFolder);

        return "$root/$folder/".static::className($name).'.php';
    }
}
