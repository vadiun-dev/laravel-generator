<?php

namespace Hitocean\Generator\Commands\Generators\Config;

use Illuminate\Support\Str;

class AtributoFactory
{
    public static function make($atributo, $tabla, ConfigFinder $finder)
    {
        $nombre = $atributo->nombre;
        $tipo = $atributo->tipo;
        $nombre_db = property_exists($atributo, 'nombre_db') && $atributo->nombre_db ? $atributo->nombre_db : Str::snake($atributo->nombre);
        $faker = property_exists($atributo, 'faker') && $atributo->faker ? $atributo->faker : self::get_faker_value($atributo);
        $default = property_exists($atributo, 'default') && $atributo->default ? $atributo->default : null;
        $unique = property_exists($atributo, 'unique') && $atributo->unique ? $atributo->unique : false;
        $validacion = property_exists($atributo, 'validacion') && $atributo->validacion ? $atributo->validacion : self::get_validacion_value($atributo, $tabla);
        $tipo_db = property_exists($atributo, 'tipo_db') && $atributo->tipo_db ? $atributo->tipo_db : self::get_tipo_db($atributo);
        $tipo_relacion = property_exists($atributo, 'tipo_relacion') && $atributo->tipo_relacion ? $atributo->tipo_relacion : "manyToOne";
        $tipo_cascada = property_exists($atributo, 'tipo_cascada') && $atributo->tipo_cascada ? $atributo->tipo_cascada : "";
        $path = property_exists($atributo, 'path') && $atributo->path ? $atributo->path : "";
        $nombre_clase = property_exists($atributo, 'class_name') && $atributo->class_name ? $atributo->class_name : ucfirst($atributo->nombre);
        $mappedBy = property_exists($atributo, 'mappedBy') && $atributo->mappedBy ? $atributo->mappedBy : "";
        $inversedBy = property_exists($atributo, 'inversedBy') && $atributo->inversedBy ? $atributo->inversedBy : "";
        $needsCreation = property_exists($atributo, 'needsCreation') ? $atributo->needsCreation : true;
        $inConstruct = property_exists($atributo, 'inConstruct') ? $atributo->inConstruct : true;

        if ($tipo === 'relation') {
            $config = $finder->find($nombre_clase);

            return new AtributoRelacion($nombre, $nombre_db, $tipo, $faker, $default, $unique, $validacion, $tipo_db, $tipo_relacion, $tipo_cascada, $path, $nombre_clase, $config, $mappedBy, $inversedBy, $inConstruct, $needsCreation);
        }

        return new AtributoConfig($nombre, $nombre_db, $tipo, $faker, $default, $unique, $validacion, $tipo_db, $inConstruct);
    }

    private static function get_faker_value($atributo): string
    {
        $tipo = strpos($atributo->tipo, '?') !== false ? str_replace("?", "", $atributo->tipo) : $atributo->tipo;

        switch ($tipo) {
            case "string":
                return 'text';
            case "int":
                return 'randomNumber()';
            case "float":
                return 'randomFloat(2)';
            default:
                return "";
        }
    }

    private static function get_validacion_value($atributo, $tabla): string
    {
        $regla = "";
        if (strpos($atributo->tipo, '?') !== false) {
            $regla .= 'nullable';
        } else {
            $regla .= 'required';
        }

        if (property_exists($atributo, 'unique')) {
            $regla .= '|unique:' . $tabla;
        }

        switch ($atributo->tipo) {
            case "int":
                $regla .= '|integer';

                break;
            case "string":
                $regla .= '|string';

                break;
            case "bool":
                $regla .= '|boolean';

                break;
            case "float":
                $regla .= '|numeric';

                break;
            default:

                $regla .= "";
        }

        return $regla;
    }

    private static function get_tipo_db($atributo): string
    {
        $tipo = strpos($atributo->tipo, '?') !== false ? str_replace("?", "", $atributo->tipo) : $atributo->tipo;

        switch ($tipo) {
            case "int":
                return "integer";
            case "float":
                return 'decimal';
            case "bool":
                return 'boolean';
            case "string":
                return 'string';
            case "relation":
                return "relation";
            default:
                return $atributo->tipo;

        }
    }
}
