<?php


namespace Hitocean\Generator\Commands\Generators\Config\DTOS;


use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\DataTransferObject;

class ActionDTO extends DataTransferObject {

    public string $folder;
    public string $name;
    public string $controllerTest;
    public string $actionTest;
    public string $route;
    public string $routeMethod;
    public array $roles;
    #[CastWith(ActionAttributeDTOArrayCaster::class)]
    public array $attributes;
    public ?string $resource;
}
