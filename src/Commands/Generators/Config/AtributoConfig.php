<?php

namespace Hitocean\Generator\Commands\Generators\Config;

use Spatie\DataTransferObject\DataTransferObject;

class AtributoConfig extends DataTransferObject
{
    private string $nombre;
    private string $nombre_db;
    private string $tipo;
    private string $faker;
    private ?string $default;
    private bool $unique;
    private string $validacion;
    private string $tipo_db;
    private bool $inContruct;
}
