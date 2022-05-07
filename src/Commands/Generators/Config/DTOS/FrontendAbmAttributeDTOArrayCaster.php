<?php

namespace Hitocean\Generator\Commands\Generators\Config\DTOS;

use Exception;
use Spatie\DataTransferObject\Caster;

class FrontendAbmAttributeDTOArrayCaster implements Caster
{
    public function cast(mixed $value): array
    {
        if (! is_array($value)) {
            throw new Exception("Can only cast arrays to Foo");
        }

        return array_map(
            fn (array $data) => new FrontendAbmAttributeDTO(...$data),
            $value
        );
    }
}
