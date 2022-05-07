<?php

namespace Hitocean\Generator\Commands\Generators\Config\DTOS;

use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\DataTransferObject;

class FrontendAbmDTO extends DataTransferObject
{
    public string $folder;
    public string $name;
    public string $route;
    #[CastWith(FrontendAbmAttributeDTOArrayCaster::class)]
    public array $attributes;
    public string $translation;
}
