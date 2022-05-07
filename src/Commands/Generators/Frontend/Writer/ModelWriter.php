<?php


namespace Hitocean\Generator\Commands\Generators\Frontend\Writer;


use Hitocean\Generator\Commands\Generators\Config\DTOS\FrontendAbmAttributeDTO;
use Hitocean\Generator\Commands\Generators\FileAdmin;
use Illuminate\Support\Str;

class ModelWriter {

    private static function path($rootName, $name): string
    {
        return 'frontend/'.$rootName.'/models/'.Str::ucfirst($name).'.ts';
    }

    public static function createFile(string $folder, string $name, array $attributes)
    {
        $directory = static::path($folder, $name);

        $lowerName = Str::lower($name);
        $upperName = Str::ucfirst($name);
        FileAdmin::writeFile(
            'model', base_path($directory),
            [
                'upperName' => $upperName,
                'lowerName' => $lowerName,
                'attributes' => static::getAttributes($attributes),
                'fromBackendAttributes' => static::getFromBackendAttributes($lowerName, $attributes),
            ],
            base_path('app/Console/Generators/Frontend/Stubs/model.stub')
        );
    }

    /**
     * @param FrontendAbmAttributeDTO[] $attributes
     * @return string
     * @throws \Exception
     */
    public static function getAttributes(array $attributes): string
    {
        $attrs = "id: number;\r\n\t";
        foreach ($attributes as $attribute)
        {
            $attrs .= "{$attribute->name}: ".static::attributeType($attribute).";\r\n\t";
        }
        $attrs .= "isDeleted: boolean;\r\n\t";

        return $attrs;
    }


    private static function attributeType(FrontendAbmAttributeDTO $attribute): string
    {
        if($attribute->isOptional)
            $optional = ' | null';
        else
            $optional = '';


        return match($attribute->type)
        {
            'file' => 'File'.$optional,
            'date' => 'Dayjs()'.$optional,
            'array' => '[]'.$optional,
            'int','float' => 'number'.$optional,
            'bool' => 'boolean'.$optional,
            'string' => 'string'.$optional
        };

    }

    private static function getFromBackendAttributes($modelName, $attributes): string
    {
        $attrs = "id: $modelName.id,\r\n\t";
        foreach ($attributes as $attribute)
        {
            $attrs .= "{$attribute->name}: $modelName.{$attribute->name},\r\n\t";
        }
        $attrs .= "isDeleted: $modelName.isDeleted,\r\n\t";
        return $attrs;
    }


}
