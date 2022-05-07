<?php

namespace Hitocean\Generator\Commands\Generators\Backend\Writers;

use Hitocean\Generator\Commands\Generators\Config\DTOS\ActionAttributeDTO;
use Hitocean\Generator\Commands\Generators\FileAdmin;
use Illuminate\Support\Str;

class ActionTestWriter extends ClassWriter
{
    public static function className($name): string
    {
        $name = Str::ucfirst($name);

        if (str_contains($name, 'ActionTest')) {
            return $name;
        }

        return $name.'ActionTest';
    }

    public static function folderName($name): string
    {
        return Str::ucfirst($name);
    }

    public static function namespace($rootFolder, $name): string
    {
        return "Tests\\Actions\\".static::rootFolder($rootFolder).'\\'.static::folderName($name).'\\'.static::className($name).'';
    }

    public static function path($rootFolder, $name): string
    {
        return "tests/Actions/".static::rootFolder($rootFolder).'/'.static::folderName($name).'/'.static::className($name).'.php';
    }

    public static function createClassFile($rootName, $className, array $attributes, bool $has_dto)
    {
        $testNamespace = static::namespace($rootName, $className);
        $folders = explode('\\', $testNamespace);
        unset($folders[count($folders) - 1]);
        $testNamespace = implode('\\', $folders);
        $directory = static::path($rootName, $className);

        FileAdmin::writeFile(
            'actiontest',
            base_path($directory),
            [
                'className' => static::className($className),
                'testNamespace' => $testNamespace,
                'example_test' => static::exampleTest($className, $attributes, $has_dto),
                'test_name' => Str::snake($className),
                'dtoImport' => $has_dto ? DTOWriter::import($rootName, $className) : "\r\n\t",
                'actionImport' => ActionWriter::import($rootName, $className),
            ]
        );
    }

    /**
     * @param string $class_name
     * @param string $route_method
     * @param string $route_name
     * @param ActionAttributeDTO[] $attributes
     * @return string
     */
    private static function exampleTest(
        string $class_name,
        array $attributes,
        bool $has_dto
    ): string {
        $action_class_name = ActionWriter::className($class_name);
        $attr = '';
        if ($has_dto) {
            $dto = DTOWriter::className($class_name);
            $attr .= "\$dto = new $dto(".static::writeAttributes($attributes).");\r\n\t";
        }
        $attr .= "$action_class_name::make()->handle(\$dto);\r\n\t";

        return $attr;
    }

    /**
     * @param ActionAttributeDTO[] $attributes
     * @param string $class_name
     * @return string
     * @throws \Exception
     */
    public static function writeAttributes(array $attributes): string
    {
        $attrs = "[\r\n\t";
        foreach ($attributes as $attribute) {
            if ($attribute->isDto()) {
                $attrs .= "'" . $attribute->name . "' => " . static::writeAttributes(
                    $attribute->attributes
                ) . ",\r\n\t";
            } else {
                $attrs .= "'" . $attribute->name . "' => " . $attribute->fakerValue() . ",\r\n\t";
            }
        }
        $attrs .= "]";

        return $attrs;
    }
}
