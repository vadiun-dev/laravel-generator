<?php

namespace Hitocean\Generator\Commands\Generators\Backend\Writers;

use Hitocean\Generator\Commands\Generators\Config\DTOS\ActionAttributeDTO;
use Hitocean\Generator\Commands\Generators\FileAdmin;
use Illuminate\Support\Str;

class GetActionWriter extends ClassWriter
{

    protected static $folder = 'Actions';

    public static function createClassFile(
        string  $rootName,
        string  $className,
        array   $attributes,
        string  $routeMethod,
        string  $routeName,
    ): void {
        $directory = static::path($rootName, $className);

        static::makeTests($rootName, $className, $routeMethod, $routeName,  $attributes);
        RequestWriter::createClassFile($rootName, 'Get'.$className, []);

        if (!file_exists(base_path($directory))) {
            RouteWriter::writeFile($routeMethod, $routeName, static::namespace($rootName, $className));
        }
        if (!file_exists(base_path($directory))) {
            GateWriter::writeFile('Get'.$className);
        }

        $lowerName = Str::lower($className);
        $upperName = Str::ucfirst($className);
        FileAdmin::writeFile(
            'get_action',
            base_path($directory),
            [
                'upperName'    => $upperName,
                'lowerName'    => $lowerName,
            ]
        );
    }

    private static function makeTests(
        $rootName,
        $className,
        string $routeMethod,
        string $routeName,
        array $attributes,
    ): void {
        GetControllerTestWriter::createClassFile($rootName, $className, $routeMethod, $routeName, $attributes);

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
        $name = 'Get'.Str::ucfirst($name);

        if (str_contains($name, 'Action')) {
            return $name;
        }
        return $name . 'Action';
    }


}
