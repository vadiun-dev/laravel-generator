<?php


namespace Hitocean\Generator\Commands\Generators\Frontend\Writer;


use Hitocean\Generator\Commands\Generators\Config\DTOS\FrontendAbmAttributeDTO;
use Hitocean\Generator\Commands\Generators\FileAdmin;
use Illuminate\Support\Str;

class TableWriter {

    public static function createFile(string $folder, string $name, array $attributes, string $translation)
    {
        $directory = static::path($folder, $name);

        $lowerName       = Str::lower($name);
        $upperName       = Str::ucfirst($name);
        $upperNameTranslation       = Str::ucfirst($translation);
        $pluralUpperName = Str::pluralStudly($upperName);
        $pluralUpperNameTranslation = Str::pluralStudly($upperNameTranslation);
        $pluralLowerName = Str::pluralStudly($lowerName);

        FileAdmin::writeFile(
            'model',
            base_path($directory),
            [
                'upperNamePluralTranslation'   => $pluralUpperNameTranslation,
                'upperNamePlural'   => $pluralUpperName,
                'lowerNamePlural'   => $pluralLowerName,
                'upperName'         => $upperName,
                'lowerName'         => $lowerName,
                'attributesColumns' => static::getColumnAttributes($attributes),
            ],
            base_path('app/Console/Generators/Frontend/Stubs/table.stub')
        );
    }

    private static function path($rootName, $name): string
    {
        return 'frontend/' . $rootName . '/components/' . Str::ucfirst($name) . 'Table.tsx';
    }

    /**
     * @param FrontendAbmAttributeDTO[] $attributes
     * @return string
     * @throws \Exception
     */
    public static function getColumnAttributes(array $attributes): string
    {
        $attrs = "";
        foreach ($attributes as $attribute) {
            $upperName = Str::ucfirst($attribute->translation);
            $lowerName = Str::lower($attribute->name);
            $column = "
                {
                  name: '$lowerName',
                  label: '$upperName',
                  options: {
                    filter: false,
                    sort: true
                  }
                },
            ";
            $attrs  .= $column. "\r\n\t";
        }

        return $attrs;
    }


    private static function attributeType(FrontendAbmAttributeDTO $attribute): string
    {
        if ($attribute->isOptional)
            $optional = ' | null';
        else
            $optional = '';


        return match ($attribute->type) {
            'file' => 'File' . $optional,
            'date' => 'Dayjs()' . $optional,
            'array' => '[]' . $optional,
            'int', 'float' => 'number' . $optional,
            'bool' => 'boolean' . $optional,
            'string' => 'string' . $optional
        };

    }

    private static function getFromBackendAttributes($modelName, $attributes): string
    {
        $attrs = "id: $modelName.id,\r\n\t";
        foreach ($attributes as $attribute) {
            $attrs .= "{$attribute->name}: $modelName.{$attribute->name},\r\n\t";
        }
        $attrs .= "isDeleted: $modelName.isDeleted,\r\n\t";
        return $attrs;
    }

    private static function getToBackendAttributes($modelName, $attributes): string
    {
        $attrs = "id: $modelName.id,\r\n\t";
        foreach ($attributes as $attribute) {
            $attrs .= "{$attribute->name}: $modelName.{$attribute->name},\r\n\t";
        }
        return $attrs;
    }
}
