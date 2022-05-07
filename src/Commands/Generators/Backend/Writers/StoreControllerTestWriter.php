<?php

namespace Hitocean\Generator\Commands\Generators\Backend\Writers;

use Hitocean\Generator\Commands\Generators\Config\DTOS\ActionAttributeDTO;
use Hitocean\Generator\Commands\Generators\FileAdmin;
use Illuminate\Support\Str;

class StoreControllerTestWriter extends ClassWriter
{

    public static function createClassFile(
        $rootName,
        $className,
        $routeMethod,
        $routeName,
        array $attributes,
    ): void {
        $directory = static::path($rootName, 'Store' . $className);
        $folders   = explode('/', $directory);
        unset($folders[count($folders) - 1]);
        $dir = implode('/', $folders);
        static::makeDirectory($dir);
        $testNamespace = static::namespace($rootName, $className);
        $folders       = explode('\\', $testNamespace);
        unset($folders[count($folders) - 1]);
        $testNamespace = implode('\\', $folders);

        $lowerName = Str::lower($className);
        $upperName = Str::ucfirst($className);
        FileAdmin::writeFile(
            'store_controller_test',
            base_path($directory),
            [
                'upperName'           => $upperName,
                'lowerName'           => $lowerName,
                'testNamespace'       => $testNamespace,
                'routeMethod'         => $routeMethod,
                'routeName'           => 'api/' . $routeName,
                'requestDataNullable' => static::writeAttributesNullable($attributes),
                'requestData'         => static::writeAttributes($attributes),
                'requestRules'        => RequestWriter::writeAttributes($attributes)
            ]
        );
    }

    public static function path($rootFolder, $name): string
    {
        return "tests/Actions/" . static::rootFolder($rootFolder) . '/' . static::folderName(
                $name
            ) . '/' . static::className($name) . '.php';
    }

    public static function folderName($name): string
    {
        return Str::ucfirst($name);
    }

    public static function className($name): string
    {
        $name = Str::ucfirst($name);

        if (str_contains($name, 'ControllerTest')) {
            return $name;
        }
        return $name . 'ControllerTest';
    }

    private static function makeDirectory($directoryName)
    {
        if (!file_exists($directoryName)) {
            if (!mkdir($concurrentDirectory = base_path($directoryName), 0777, true) && !is_dir($concurrentDirectory)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
            }
        }
    }

    public static function namespace($rootFolder, $name): string
    {
        return "Tests\\Actions\\" . static::rootFolder($rootFolder) . '\\' . static::folderName(
                $name
            ) . '\\' . static::className($name) . '';
    }

    /**
     * @param ActionAttributeDTO[] $attributes
     * @return string
     */
    public static function writeAttributesNullable(array $attributes): string
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

    /**
     * @param ActionAttributeDTO[] $attributes
     * @return string
     */
    public static function writeAttributes(array $attributes): string
    {
        $attrs = "";
        foreach ($attributes as $attribute) {
            $attrs .= "'{$attribute->name}' => {$attribute->fakerValue()},\r\n\t";
        }
        return $attrs;
    }

}
