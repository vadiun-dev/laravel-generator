<?php

namespace Hitocean\Generator\Commands\Generators\Backend\Writers;

use Hitocean\Generator\Commands\Generators\Config\DTOS\ModelAttributeDTO;
use Hitocean\Generator\Commands\Generators\FileAdmin;
use Illuminate\Support\Str;

class FactoryWriter extends ClassWriter
{
    public static function className($name): string
    {
        $name = Str::ucfirst($name);

        if (str_contains($name, 'Factory')) {
            return $name;
        }

        return $name.'Factory';
    }

    public static function namespace($rootFolder, $name): string
    {
        return "Database\\Factories\\".static::className($name);
    }

    public static function path($rootFolder, $name): string
    {
        return "database/factories/".static::className($name).'.php';
    }

    public static function createClassFile(string $rootName, string $className, array $attributes)
    {
        $directory = static::path($rootName, $className);
        FileAdmin::writeFile(
            'factory',
            base_path($directory),
            [
                'className' => static::className($className),
                'modelName' => ModelWriter::className($className),
                'modelImport' => ModelWriter::import($rootName, $className),
                'attributes' => static::writeAttributes($attributes),
            ]
        );
    }

    /**
     * @param ModelAttributeDTO[] $attributes
     * @return string
     * @throws \Exception
     */
    public static function writeAttributes(array $attributes): string
    {
        $attrs = '';
        foreach ($attributes as $attribute) {
            if (static::canBeNull($attribute->type)) {
                $attrs .= 'null';
            } else {
                $attrs .= "'".$attribute->name."' => ".'$this->faker->' . static::attributeType($attribute->type) . ",\r\n\t";
            }
        }

        return $attrs;
    }

    private static function canBeNull(string $type): string
    {
        return str_contains($type, '?');
    }

    private static function attributeType(string $type): string
    {
        if (str_contains($type, '?')) {
            $type = Str::remove('?', $type);
        }
        if (! in_array($type, ['string', 'int', 'float', 'date', 'bool', 'datetime'])) {
            throw new \Exception('el tipo ' . $type . ' no puede ser procesado');
        }

        return match ($type) {
            'int' => 'randomNumber()',
            'float' => 'randomFloat(2)',
            'string' => 'name',
            'date', 'datetime' => 'now()',
            'bool' => 'boolean',
            default => $type
        };
    }
}
