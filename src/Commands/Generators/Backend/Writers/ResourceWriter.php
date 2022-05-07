<?php

namespace Hitocean\Generator\Commands\Generators\Backend\Writers;

use Hitocean\Generator\Commands\Generators\Config\DTOS\ModelAttributeDTO;
use Hitocean\Generator\Commands\Generators\FileAdmin;
use Illuminate\Support\Str;

class ResourceWriter extends ClassWriter
{
    protected static $folder = 'Resources';

    public static function createClassFile($rootName, $className, $attributes)
    {
        $directory = static::path($rootName, $className);
        FileAdmin::writeFile(
            'resource',
            base_path($directory),
            [
                'rootFolder' => static::rootFolder($rootName),
                'className' => static::className($className),
                'attributes' => static::writeAttributes($attributes),
                'modelName' => ModelWriter::className($className),
                'modelImport' => ModelWriter::import($rootName, $className),
            ]
        );
    }

    public static function className($name): string
    {
        $name = Str::ucfirst($name);

        if (str_contains($name, 'Resource')) {
            return $name;
        }

        return $name . 'Resource';
    }

    /**
     * @param ModelAttributeDTO[] $attributes
     * @return string
     * @throws \Exception
     */
    public static function writeAttributes(array $attributes): string
    {
        $attrs = "'id' => " . '$this->id,' . "\r\n\t";
        foreach ($attributes as $attribute) {
            $attrs .= "'" . Str::snake($attribute->name) . "' => " . '$this->' . $attribute->name . ",\r\n\t";
        }

        return $attrs;
    }
}
