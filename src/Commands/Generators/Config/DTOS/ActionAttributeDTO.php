<?php

namespace Hitocean\Generator\Commands\Generators\Config\DTOS;

use Illuminate\Support\Str;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\DataTransferObject;

class ActionAttributeDTO extends DataTransferObject
{
    public string $name;
    public string $type;
    #[CastWith(ActionAttributeDTOArrayCaster::class)]
    public array  $attributes;

    public function fakerValue(): string
    {
        if ($this->isOptional()) {
            $type = Str::remove('?', $this->type);
        } else if ($this->isArray()) {
            $type = Str::remove('*', $this->type);
        } else {
            $type = $this->type;
        }
        if (!in_array($type, ['string', 'int', 'float', 'date', 'bool', 'datetime', 'file'])) {
            throw new \Exception('el tipo ' . $type . ' no puede ser procesado');
        }

        return match ($type) {
            'int' => '$this->faker->randomNumber()',
            'float' => '$this->faker->randomFloat(2)',
            'string' => '$this->faker->name',
            'date', 'datetime' => 'now()',
            'bool' => '$this->faker->boolean',
            'file' => "UploadedFile::fake()->image('prueba.png')",
            default => $type
        };
    }

    public function isOptional(): bool
    {
        return str_contains($this->type, '?');
    }

    public function isArray(): bool
    {
        return str_contains($this->type, '*');
    }

    public function isDto(): bool
    {
        $type = $this->type;
        if (str_contains($type, '?') || str_contains($type, '*')) {
            $type = Str::remove(['?', '*'], $type);
        }
        return ctype_upper($type[0]);
    }
}
