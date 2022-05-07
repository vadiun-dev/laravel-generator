<?php


namespace Hitocean\Generator\Commands\Generators\Config\DTOS;


use Spatie\DataTransferObject\DataTransferObject;

class FrontendAbmAttributeDTO extends DataTransferObject {

    public string $name;
    public string $type;
    public string $translation;
    public bool $isOptional;

    public function isNumeric(): bool
    {
        return match($this->type){
            'int','float' => true,
            default => false
        };
    }
}
