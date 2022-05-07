<?php

namespace Hitocean\Generator\Commands\Generators\Backend\Writers;

use Hitocean\Generator\Commands\Generators\Config\DTOS\ActionAttributeDTO;
use Hitocean\Generator\Commands\Generators\FileAdmin;
use Illuminate\Support\Str;

class DeleteActionTestWriter extends ClassWriter
{
    public static function createClassFile($rootName, $className, array $attributes, string $table_name)
    {
        $testNamespace = static::namespace($rootName, $className);
        $folders       = explode('\\', $testNamespace);
        unset($folders[count($folders) - 1]);
        $directory = static::path($rootName, 'Delete' . $className);

        $lowerName = Str::lower($className);
        $upperName = Str::ucfirst($className);

        FileAdmin::writeFile(
            'delete_action_test', base_path($directory),
            [
                'upperName' => $upperName,
                'lowerName' => $lowerName,
                'assertDataTable' => static::assertData($attributes),
                'dtoDataNullable' => static::dtoDataNullable($attributes),
                'dtoData' => static::dtoData($attributes),
                'tableName' => $table_name
            ]
        );
    }

    public static function namespace($rootFolder, $name): string
    {
        return "Tests\\Actions\\" . static::rootFolder($rootFolder) . '\\' . static::folderName(
                $name
            ) . '\\' . static::className($name) . '';
    }

    public static function folderName($name): string
    {
        return Str::ucfirst($name);
    }

    public static function className($name): string
    {
        $name = Str::ucfirst($name);

        if (str_contains($name, 'ActionTest')) {
            return $name;
        }
        return $name . 'ActionTest';
    }

    public static function path($rootFolder, $name): string
    {
        return "tests/Actions/" . static::rootFolder($rootFolder) . '/' . static::folderName(
                $name
            ) . '/' . static::className($name) . '.php';
    }

    /**
     * @param ActionAttributeDTO[] $attributes
     * @param string $class_name
     * @return string
     * @throws \Exception
     */
    public static function assertData(array $attributes): string
    {
        $attrs = "";
        foreach ($attributes as $attribute) {
            $attrs .= "'{$attribute->name}' => \$dto->{$attribute->name} ,\r\n\t";
        }
        return $attrs;
    }

    /**
     * @param ActionAttributeDTO[] $attributes
     * @param string $class_name
     * @return string
     * @throws \Exception
     */
    public static function dtoData(array $attributes): string
    {
        $attrs = "";
        foreach ($attributes as $attribute) {
            $attrs .= "'{$attribute->name}' => {$attribute->fakerValue()},\r\n\t";
        }
        return $attrs;
    }

    /**
     * @param ActionAttributeDTO[] $attributes
     * @param string $class_name
     * @return string
     * @throws \Exception
     */
    public static function dtoDataNullable(array $attributes): string
    {
        $attrs = "";
        foreach ($attributes as $attribute) {
            if ($attribute->isOptional()) {
                $attrs .= "'{$attribute->name}' => null,\r\n\t";
            } else {
                $attrs .= "'{$attribute->name}' => {$attribute->fakerValue()},\r\n\t";
            }
        }
        return $attrs;
    }
}
