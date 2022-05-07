<?php

namespace Hitocean\Generator\Commands\Generators\Config\DTOS;

use Spatie\DataTransferObject\DataTransferObject;

class ModelAttributeDTO extends DataTransferObject
{
    public string $name;
    public string $type;
}
