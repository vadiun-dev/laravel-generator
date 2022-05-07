<?php


namespace Hitocean\Generator\Commands\Generators\Frontend\Writer;


use Hitocean\Generator\Commands\Generators\Config\DTOS\FrontendAbmAttributeDTO;
use Hitocean\Generator\Commands\Generators\FileAdmin;
use Illuminate\Support\Str;

class FormInitialValueWriter {
    private static function path($rootName, $name): string
    {
        $upperName = Str::ucfirst($name);
        return "frontend/$rootName/components/{$upperName}Form/{$upperName}FormInitialValues.tsx";
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
                'initialValuesAttributes' => static::getInitialValuesAttributes($lowerName, $attributes),
            ],
            base_path('app/Console/Generators/Frontend/Stubs/form_initial_value.stub')
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
}
