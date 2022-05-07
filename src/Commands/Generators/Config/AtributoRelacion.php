<?php

namespace Hitocean\Generator\Commands\Generators\Config;

class AtributoRelacion extends AtributoConfig
{
    private string $tipo_relacion;
    private string $tipo_cascada;
    private string $path;
    private string    $nombre_clase;
    private ConfigDTO $config;
    private string    $mappedBy;
    private string $inversedBy;
    private bool $needsCreation;

    public function __construct(string $nombre, string $nombre_db, string $tipo, string $faker, ?string $default, bool $unique, string $validacion, string $tipo_db, string $tipo_relacion, string $tipo_cascada, string $path, string $nombre_clase, ConfigDTO $config, string $mappedBy, string $inversedBy, bool $inConstruct, bool $needsCreation)
    {
        parent::__construct($nombre, $nombre_db, $tipo, $faker, $default, $unique, $validacion, $tipo_db, $inConstruct);
        $this->tipo_relacion = $tipo_relacion;
        $this->tipo_cascada = $tipo_cascada;
        $this->path = $path;
        $this->nombre_clase = $nombre_clase;
        $this->config = $config;
        $this->mappedBy = $mappedBy;
        $this->inversedBy = $inversedBy;
        $this->needsCreation = $needsCreation;
    }
}
