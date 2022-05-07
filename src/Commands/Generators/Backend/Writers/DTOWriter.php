<?php


namespace Hitocean\Generator\Commands\Generators\Backend\Writers;


use Hitocean\Generator\Commands\Generators\Config\DTOS\ActionAttributeDTO;
use Hitocean\Generator\Commands\Generators\Config\DTOS\ModelAttributeDTO;
use Hitocean\Generator\Commands\Generators\FileAdmin;
use Illuminate\Support\Str;

class DTOWriter extends ClassWriter {


    public static function className($name): string
    {
        $name = Str::ucfirst($name);

        if(str_contains($name, 'DTO'))
            return $name;
        return $name.'DTO';
    }

    public static function namespace($rootFolder, $name): string
    {
        $root = self::rootNamespace($rootFolder);
        return "$root\\Actions\\DTOS\\".static::className($name);
    }

    public static function path($rootFolder, $name): string
    {
        $root = self::rootPath($rootFolder);
        return "$root/Actions/DTOS/".static::className($name).'.php';
    }

    public static function createClassFile($rootName, $className, $attributes): void
    {
        $directory = static::path($rootName, $className);
        FileAdmin::writeFile(
            'dto', base_path($directory),
            [
                'rootFolder' => static::rootFolder($rootName),
                'className' => static::className($className),
                'attributes' => static::writeAttributes($rootName, $attributes)
            ]
        );
    }

    /**
     * @param ActionAttributeDTO[] $attributes
     * @return string
     * @throws \Exception
     */
    public static function writeAttributes(string $rootName, array $attributes): string
    {
        $attrs = '';
        foreach ($attributes as $attribute)
        {
            if(static::isDto($attribute))
                static::createClassFile($rootName, static::typeToClassName($attribute->type), $attribute->attributes);

            $attrs .= 'public '.static::attributeType($attribute->type).' $'.$attribute->name.";\r\n\t";
        }
        return $attrs;
    }

    private static function isDto(ActionAttributeDTO $attribute): bool
    {
        $type = $attribute->type;
        if(str_contains($type, '?') || str_contains($type, '*'))
        {
            $type = Str::remove(['?', '*'], $type);
        }
        return ctype_upper($type[0]);
    }

    private static function typeToClassName(string $type): string
    {
        if(str_contains($type, '?') || str_contains($type, '*'))
            return Str::remove(['?', '*'], $type);
        return $type;
    }

    private static function attributeType(string $type): string
    {
        $optional = '';
        if(str_contains($type, '?'))
        {
            $optional = '?';
            $type = Str::remove('?', $type);
        }
        if(str_contains($type, '*'))
        {
            return 'array';
        }
        if(!in_array($type, ['string', 'int', 'float', 'file', 'array', 'bool', 'date']) && !ctype_upper($type[0]))
            throw new \Exception('el tipo '. $type. ' no puede ser procesado');

        switch ($type)
        {
            case 'file':
                return $optional.'UploadedFile';
            case 'date':
                return $optional.'Carbon';
            default:
                return $optional.$type;
        }
    }
}
