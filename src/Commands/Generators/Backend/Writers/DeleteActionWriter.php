<?php

namespace Hitocean\Generator\Commands\Generators\Backend\Writers;

use Hitocean\Generator\Commands\Generators\Config\DTOS\ActionAttributeDTO;
use Hitocean\Generator\Commands\Generators\FileAdmin;
use Illuminate\Support\Str;

class DeleteActionWriter extends ClassWriter
{
    protected static $folder = 'Actions';

    public static function createClassFile(
        string  $rootName,
        string  $className,
        array   $attributes,
        string  $routeMethod,
        string  $routeName,
        string $table_name
    ): void {
        $directory = static::path($rootName, $className);

        static::makeTests($rootName, $className, $routeMethod, $routeName, $attributes, $table_name);
        RequestWriter::createClassFile($rootName, 'Delete'.$className, []);
        if (! file_exists(base_path($directory))) {
            RouteWriter::writeFile($routeMethod, $routeName, static::namespace($rootName, $className));
        }
        if (! file_exists(base_path($directory))) {
            GateWriter::writeFile('Delete'.$className);
        }

        $lowerName = Str::lower($className);
        $upperName = Str::ucfirst($className);
        FileAdmin::writeFile(
            'delete_action',
            base_path($directory),
            [
                'upperName' => $upperName,
                'lowerName' => $lowerName,
            ]
        );
    }

    private static function makeTests(
        $rootName,
        $className,
        string $routeMethod,
        string $routeName,
        array $attributes,
        string $table_name
    ): void {
        DeleteControllerTestWriter::createClassFile($rootName, $className, $routeMethod, $routeName, $attributes);
        DeleteActionTestWriter::createClassFile($rootName, $className, $attributes, $table_name);
    }

    /**
     * @param ActionAttributeDTO[] $attributes
     * @return string
     */
    public static function writeAttributes($attributes): string
    {
        $str = "";
        foreach ($attributes as $attribute) {
            $str .= "'{$attribute->name}' => \$dto->{$attribute->name},\r\n\t";
        }

        return $str;
    }

    public static function className($name): string
    {
        $name = 'Delete'.Str::ucfirst($name);

        if (str_contains($name, 'Action')) {
            return $name;
        }

        return $name . 'Action';
    }
}
