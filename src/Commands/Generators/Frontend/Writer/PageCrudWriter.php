<?php


namespace Hitocean\Generator\Commands\Generators\Frontend\Writer;


use Hitocean\Generator\Commands\Generators\FileAdmin;
use Illuminate\Support\Str;

class PageCrudWriter {
    public static function createFile(string $folder, string $name, string $translation)
    {
        $directory = static::path($folder, $name);

        $lowerName = Str::lower($name);
        $upperName = Str::ucfirst($name);
        $pluralUpperName = Str::pluralStudly($upperName);
        $pluralLowerName = Str::pluralStudly($lowerName);
        $upperNameTranslation = Str::ucfirst($translation);
        $pluralUpperNameTranslation = Str::pluralStudly($upperNameTranslation);

        FileAdmin::writeFile(
            'model',
            base_path($directory),
            [
                'upperNameTranslation' => $upperNameTranslation,
                'upperNamePluralTranslation' => $pluralUpperNameTranslation,
                'upperNamePlural' => $pluralUpperName,
                'lowerNamePlural' => $pluralLowerName,
                'upperName'  => $upperName,
                'lowerName'  => $lowerName,
            ],
            base_path('app/Console/Generators/Frontend/Stubs/page_crud.stub')
        );
    }

    private static function path($rootName, $name): string
    {
        $upperName = Str::ucfirst($name);
        return "frontend/$rootName/pages/{$upperName}CrudPage.tsx";
    }
}
