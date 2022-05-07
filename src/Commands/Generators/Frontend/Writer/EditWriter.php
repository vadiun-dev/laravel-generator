<?php


namespace Hitocean\Generator\Generators\Frontend\Writer;


use Hitocean\Generator\Generators\FileAdmin;
use Illuminate\Support\Str;

class EditWriter {

    public static function createFile(string $folder, string $name, string $translation)
    {
        $directory = static::path($folder, $name);

        $lowerName = Str::lower($name);
        $upperName = Str::ucfirst($name);
        $upperNameTranslation = Str::ucfirst($translation);

        FileAdmin::writeFile(
            'model',
            base_path($directory),
            [
                'upperNameTranslation'  => $upperNameTranslation,
                'upperName'  => $upperName,
                'lowerName'  => $lowerName,
            ],
            base_path('app/Console/Generators/Frontend/Stubs/edit.stub')
        );
    }

    private static function path($rootName, $name): string
    {
        $upperName = Str::ucfirst($name);
        return "frontend/$rootName/components/{$upperName}Edit.tsx";
    }
}
