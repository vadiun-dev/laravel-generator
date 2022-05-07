<?php

namespace Hitocean\Generator\Commands\Generators\Backend\Writers;

use Hitocean\Generator\Commands\Generators\Config\DTOS\ActionAttributeDTO;
use Hitocean\Generator\Commands\Generators\FileAdmin;
use Illuminate\Support\Str;

class RequestWriter extends ClassWriter
{
    protected static $folder = 'Requests';

    public static function createClassFile($rootName, $className, $attributes): void
    {
        $directory = static::path($rootName, $className);
        FileAdmin::writeFile(
            'request',
            base_path($directory),
            [
                'rootFolder' => static::rootFolder($rootName),
                'className' => static::className($className),
                'attributes' => static::writeAttributes($attributes),
                'gate' => static::gate($className),
            ]
        );
    }

    public static function gate(string $class_name): string
    {
        $gate_name = GateWriter::gateName($class_name);

        return "Gate::check('$gate_name', \$this->user());";
    }

    public static function className($name): string
    {
        $name = Str::ucfirst($name);

        if (str_contains($name, 'Request')) {
            return $name;
        }

        return $name . 'Request';
    }

    /**
     * @param ActionAttributeDTO[] $attributes
     * @return string
     */
    public static function writeAttributes($attributes, ?string $prefix = null): string
    {
        $str = "";
        foreach ($attributes as $attribute) {
            if ($prefix) {
                $str .= "'$prefix." . Str::snake($attribute->name) . "' => '" . static::attributeType(
                    $attribute->type
                ) . "',\r\n\t";
            } else {
                $str .= "'" . Str::snake($attribute->name) . "' => '" . static::attributeType($attribute->type) . "',\r\n\t\t";
            }
            if (static::isDto($attribute) && str_contains($attribute->type, '*')) {
                $str .= static::writeAttributes($attribute->attributes, Str::snake($attribute->name).'.*');
            } else {
                $str .= static::writeAttributes($attribute->attributes, Str::snake($attribute->name));
            }
        }

        return $str;
    }

    private static function attributeType(string $type): string
    {
        $isArray = false;
        $is_optional = false;
        if (str_contains($type, '?')) {
            $is_optional = true;
            $type = Str::remove('?', $type);
        }
        if (str_contains($type, '*')) {
            $isArray = true;
            $type = Str::remove('*', $type);
        }

        if (! in_array($type, ['string', 'int', 'float', 'file', 'array', 'bool', 'date']) && ! ctype_upper($type[0])) {
            throw new \Exception('el tipo ' . $type . ' no puede ser procesado');
        }

        if (ctype_upper($type[0])) {
            if ($is_optional) {
                return 'nullable';
            } elseif ($isArray) {
                return 'required | array';
            } else {
                return 'required';
            }
        }

        if ($is_optional) {
            $requestType = 'nullable|';
        } else {
            $requestType = 'required|';
        }

        switch ($type) {
            case 'file':
                $requestType .= 'file';

                break;
            case 'int':
                $requestType .= 'integer';

                break;
            case 'float':
                $requestType .= 'numeric';

                break;
            case 'bool':
                $requestType .= 'boolean';

                break;
            default:
                $requestType .= $type;

                break;
        }

        return $requestType;
    }

    private static function isDto(ActionAttributeDTO $attribute): bool
    {
        $type = $attribute->type;
        if (str_contains($type, '?') || str_contains($type, '*')) {
            $type = Str::remove(['?', '*'], $type);
        }

        return ctype_upper($type[0]);
    }
}
