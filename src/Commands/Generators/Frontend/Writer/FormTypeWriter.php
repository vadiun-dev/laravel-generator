<?php


namespace Hitocean\Generator\Commands\Generators\Frontend\Writer;


use Hitocean\Generator\Commands\Generators\Config\DTOS\FrontendAbmAttributeDTO;
use Hitocean\Generator\Commands\Generators\FileAdmin;
use Illuminate\Support\Str;

class FormTypeWriter {
    private static function path($rootName, $name): string
    {
        $upperName = Str::ucfirst($name);
        return "frontend/$rootName/components/{$upperName}Form/{$upperName}FormType.tsx";
    }

    public static function createFile(string $folder, string $name, array $attributes)
    {
        $directory = static::path($folder, $name);

        $lowerName = Str::lower($name);
        $upperName = Str::ucfirst($name);
        FileAdmin::writeFile(
            'model', base_path($directory),
            [
                'upperName' => $upperName,
                'lowerName' => $lowerName,
                'toBackendAttributes' => static::getToBackendAttributes($lowerName, $attributes),
                'typesOmited' => static::typesOmited($attributes),
                'attributesType' => static::attributeTypes($attributes),
                'attributesFromModel' => static::attributeFromModel($attributes, $lowerName)
            ],
            base_path('app/Console/Generators/Frontend/Stubs/form_type.stub')
        );
    }

    /**
     * @param FrontendAbmAttributeDTO[] $attributes
     * @return string
     * @throws \Exception
     */
    public static function getYupAttributes(array $attributes): string
    {
        $attrs = "";
        foreach ($attributes as $attribute)
        {
            $attrs .= "{$attribute->name}: Yup.".static::yupType($attribute).",\r\n\t";
        }
        return $attrs;
    }

    /**
     * @param FrontendAbmAttributeDTO[] $attributes
     * @return string
     * @throws \Exception
     */
    public static function typesOmited(array $attributes): string
    {
        $attrs = "";
        foreach ($attributes as $attribute)
        {
            if($attribute->isNumeric())
                $attrs .= "| '{$attribute->name}' ";
        }
        return $attrs;
    }

    /**
     * @param FrontendAbmAttributeDTO[] $attributes
     * @return string
     * @throws \Exception
     */
    public static function attributeTypes(array $attributes): string
    {
        $attrs = "";
        foreach ($attributes as $attribute)
        {
            if($attribute->isNumeric())
                $attrs .= "{$attribute->name}: string;\r\n\t";
        }
        return $attrs;
    }

    /**
     * @param FrontendAbmAttributeDTO[] $attributes
     * @return string
     * @throws \Exception
     */
    public static function attributeFromModel(array $attributes, string $lowerName): string
    {
        $attrs = "";
        foreach ($attributes as $attribute)
        {
            if($attribute->isNumeric())
                $attrs .= "{$attribute->name}: String({$lowerName}.{$attribute->name}),\r\n\t";
        }
        return $attrs;
    }

    private static function yupType(FrontendAbmAttributeDTO $attribute): string
    {
        if($attribute->isOptional)
            $optional = '.nullable()';
        else
            $optional = ".required('El {$attribute->name} es requerido')";


        return match($attribute->type)
        {
            'date' => 'Dayjs()'.$optional,
            'array' => 'array()'.$optional,
            'int','float' => 'number()'.$optional,
            'bool' => 'boolean()'.$optional,
            'string','file' => 'string()'.$optional
        };

    }

    private static function getInitialValuesAttributes($modelName, $attributes): string
    {
        $attrs = "";
        foreach ($attributes as $attribute)
        {
            $attrs .= "{$attribute->name}: ".static::initialValueAttribute($attribute).",\r\n\t";
        }
        return $attrs;
    }

    private static function initialValueAttribute(FrontendAbmAttributeDTO $attribute): string
    {

        return match($attribute->type)
        {
            'date' => 'Dayjs()',
            'array' => '[]',
            'int','float' => "''",
            'bool' => 'false',
            'string','file' => "''"
        };
    }

    private static function getToBackendAttributes($modelName, $attributes): string
    {
        $attrs = "id: $modelName.id,\r\n\t";
        foreach ($attributes as $attribute)
        {
            $attrs .= "{$attribute->name}: $modelName.{$attribute->name},\r\n\t";
        }
        return $attrs;
    }
}
