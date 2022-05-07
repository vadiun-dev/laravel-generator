<?php

namespace Hitocean\Generator\Commands\Generators\Backend\Writers;

use Hitocean\Generator\Commands\Generators\Config\DTOS\ActionAttributeDTO;
use Hitocean\Generator\Commands\Generators\FileAdmin;
use Illuminate\Support\Str;

class StoreActionWriter extends ClassWriter
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
        DTOWriter::createClassFile($rootName, 'Store'.$className, $attributes);
        RequestWriter::createClassFile($rootName, 'Store'.$className, $attributes);

        if (!file_exists(base_path($directory))) {
            RouteWriter::writeFile($routeMethod, $routeName, static::namespace($rootName, $className));
        }
        if (!file_exists(base_path($directory))) {
            GateWriter::writeFile('Store'.$className);
        }

        $lowerName = Str::lower($className);
        $upperName = Str::ucfirst($className);
        FileAdmin::writeFile(
            'store_action',
            base_path($directory),
            [
                'upperName'    => $upperName,
                'lowerName'    => $lowerName,
                'createFields' => static::writeAttributes($attributes),
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
        StoreControllerTestWriter::createClassFile($rootName, $className, $routeMethod, $routeName, $attributes);
            StoreActionTestWriter::createClassFile($rootName, $className, $attributes, $table_name);

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
        $name = Str::ucfirst($name);
        $name = 'Store'.$name;
        if (str_contains($name, 'Action')) {
            return $name;
        }
        return $name . 'Action';
    }


}
