<?php


namespace Hitocean\Generator\Commands\Generators\Config\DTOS;



use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\DataTransferObject;

class ConfigDTO extends DataTransferObject
{
    public string $modelName;
    public string $folder;
    #[CastWith(AttributeDTOArrayCaster::class)]
    public array $attributes;
    public string $tableName;
    public bool $has_abm;

}
