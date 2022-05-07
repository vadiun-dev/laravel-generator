<?php


namespace Hitocean\Generator\Commands\Generators\Frontend\Writer;


use Hitocean\Generator\Commands\Generators\FileAdmin;
use Illuminate\Support\Str;

class ServiceWriter {

    public static function createFile(string $folder, string $name, string $route)
    {
        $directory = static::path($folder, $name);

        $lowerName = Str::lower($name);
        $upperName = Str::ucfirst($name);
        $pluralUpperName = Str::pluralStudly($upperName);
        FileAdmin::writeFile(
            'model',
            base_path($directory),
            [
                'upperNamePlural' => $pluralUpperName,
                'upperName'  => $upperName,
                'lowerName'  => $lowerName,
                'route' => $route
            ],
            base_path('app/Console/Generators/Frontend/Stubs/service.stub')
        );
    }

    private static function path($rootName, $name): string
    {
        $upperName = Str::ucfirst($name);
        return "frontend/$rootName/services/{$upperName}Service.ts";
    }
}
