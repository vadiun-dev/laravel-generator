<?php


namespace Hitocean\Generator\Commands\Generators\Config\DTOS;


use Hitocean\Generator\Commands\Generators\Config\DTOS\ActionDTO;
use Hitocean\Generator\Commands\Generators\Config\DTOS\ModelAttributeDTO;
use Exception;
use Spatie\DataTransferObject\Caster;

class AttributeDTOArrayCaster implements Caster
{
    public function cast(mixed $value): array
    {
        if (! is_array($value)) {
            throw new Exception("Can only cast arrays to Foo");
        }

        return array_map(
            fn (array $data) => new ModelAttributeDTO(...$data),
            $value
        );
    }
}
