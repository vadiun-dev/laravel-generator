<?php


namespace Hitocean\Generator\Commands\Generators\Backend\Writers;


use Hitocean\Generator\Commands\Generators\FileAdmin;
use Illuminate\Support\Str;

class ActionWriter extends ClassWriter {

    protected static $folder = 'Actions';

    public static function createClassFile(
        string $rootName,
        string $className,
        array $attributes,
        bool $hasDto,
        bool $hasRequest,
        bool $hasActioTest,
        string $routeMethod,
        string $routeName,
        ?string $resource): void
    {
        $directory = static::path($rootName, $className);

        static::makeTests($rootName, $className, $routeMethod, $routeName, $hasActioTest, $attributes, $hasDto);

        if(!file_exists(base_path($directory)))
            RouteWriter::writeFile($routeMethod, $routeName, static::namespace($rootName, $className));
        if(!file_exists(base_path($directory)))
            GateWriter::writeFile($className);

            FileAdmin::writeFile(
            'action', base_path($directory), [
                'rootFolder'       => static::rootFolder($rootName),
                'className'        => static::className($className),
                'dtoImport'        => static::dtoImport($rootName, $className, $attributes, $hasDto),
                'requestImport'    => static::requestImport($rootName, $className, $attributes, $hasRequest),
                'handleParams'     => static::handleParams($className, $hasDto),
                'controllerParams' => static::controllerParams($className, $hasRequest, $routeName),
                'resourceDoc'      => static::resourceDoc($resource, $rootName),
                'resourceModelDoc' => static::resourceModelDoc($resource, $rootName),
            ]
        );
    }

    public static function className($name): string
    {
        $name = Str::ucfirst($name);

        if (str_contains($name, 'Action'))
            return $name;
        return $name . 'Action';
    }

    private static function makeTests($rootName, $className, string $routeMethod, string $routeName, bool $hasActioTest, array $attributes, bool $has_dto): void
    {
        ControllerTestWriter::createClassFile($rootName, $className, $routeMethod, $routeName, $attributes, $has_dto);
        if($hasActioTest)
            ActionTestWriter::createClassFile($rootName, $className, $attributes, $has_dto);
    }

    private static function dtoImport(string $rootName, string $className, array $attributes, bool $hasDto): string
    {
        if (!$hasDto)
            return '';
        DTOWriter::createClassFile($rootName, $className, $attributes);
        return DTOWriter::import($rootName, $className);

    }

    private static function requestImport(
        string $rootName,
        string $className,
        array $attributes,
        bool $hasRequest): string
    {
        if (!$hasRequest)
            return '';
        RequestWriter::createClassFile($rootName, $className, $attributes);
        return RequestWriter::import($rootName, $className);

    }

    private static function handleParams(string $className, bool $hasDto): string
    {
        if ($hasDto)
            return DTOWriter::className($className) . ' $dto';
        return '';
    }

    private static function controllerParams(string $className, bool $hasRequest, string $route_name): string
    {
        $params = [];
        if ($hasRequest)
            $params[] = RequestWriter::className($className) . ' $request';
        if(Str::contains($route_name, '{id}'))
            $params[] = 'int $id';
        return implode(", ", $params);
    }

    private static function resourceDoc(?string $resource, string $rootName): string
    {
        if ($resource)
            return '* @apiResource ' . ResourceWriter::namespace($rootName, $resource);
        return '';
    }

    private static function resourceModelDoc(?string $resource, string $rootName): string
    {
        if ($resource)
            return '* @apiResourceModel ' . ModelWriter::namespace(
                    $rootName, $resource
                );
        return '';
    }

}
