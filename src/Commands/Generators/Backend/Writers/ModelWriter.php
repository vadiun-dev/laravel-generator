<?php

namespace Hitocean\Generator\Commands\Generators\Backend\Writers;

use Hitocean\Generator\Commands\Generators\Config\DTOS\ActionAttributeDTO;
use Hitocean\Generator\Commands\Generators\Config\DTOS\ModelAttributeDTO;
use Hitocean\Generator\Commands\Generators\FileAdmin;
use Illuminate\Support\Str;

class ModelWriter extends ClassWriter
{
    protected static $folder = 'Models';

    public static function createClassFile(
        string $rootName,
        string $className,
        string $tableName,
        array  $attributes,
        bool   $hasMigration,
        bool   $hasFactory,
        bool   $has_abm
    ) {
        $directory = static::path($rootName, $className);
        if ($hasMigration) {
            MigrationWriter::createClassFile($rootName, $className, $attributes, $tableName);
        }
        if ($hasFactory) {
            FactoryWriter::createClassFile($rootName, $className, $attributes);
        }
        ResourceWriter::createClassFile($rootName, $className, $attributes);
        ResourceHelperTestWriter::createClassFile($rootName, $className, $attributes);
        $route_path = explode("_", Str::snake($className));
        $route_path[count($route_path) - 1] = Str::plural($route_path[count($route_path) - 1]);
        foreach ($route_path as $key => $p) {
            $route_path[$key] = Str::lower($p);
        }
        $route_path = implode("-", $route_path);

        if ($has_abm) {
            $action_attributes = static::mapAttributesToActionAttributes($attributes);
            StoreActionWriter::createClassFile(
                $rootName,
                $className,
                $action_attributes,
                'post',
                $route_path,
                $tableName
            );
            UpdateActionWriter::createClassFile(
                $rootName,
                $className,
                $action_attributes,
                'put',
                $route_path.'/{id}',
                $tableName
            );
            FindActionWriter::createClassFile(
                $rootName,
                $className,
                $action_attributes,
                'get',
                $route_path.'/{id}',
            );
            GetActionWriter::createClassFile(
                $rootName,
                $className,
                $action_attributes,
                'get',
                $route_path,
            );
            DeleteActionWriter::createClassFile(
                $rootName,
                $className,
                $action_attributes,
                'delete',
                $route_path.'/{id}',
                $tableName
            );
        }

        FileAdmin::writeFile(
            'model',
            base_path($directory),
            [
                'rootFolder' => static::rootFolder($rootName),
                'className' => static::className($className),
                'tableName' => $tableName,
                'attributes' => static::writeAttributes($attributes),
            ]
        );
    }

    public static function className($name): string
    {
        return Str::ucfirst($name);
    }

    /**
     * @param ModelAttributeDTO[] $attributes
     * @return string
     */
    private static function writeAttributes(array $attributes): string
    {
        return implode(', ', array_map(fn (ModelAttributeDTO $m) => "'{$m->name}'", $attributes));
    }

    public static function modelNameVar(string $class_name): string
    {
        return Str::snake($class_name);
    }

    /**
     * @param ModelAttributeDTO[] $attributes
     */
    public static function mapAttributesToActionAttributes($attributes): array
    {
        $atrs = [];
        foreach ($attributes as $attribute) {
            $atrs[] = new ActionAttributeDTO([
                'name' => $attribute->name,
                'type' => $attribute->type,
                'attributes' => [],
                                           ]);
        }

        return $atrs;
    }
}
