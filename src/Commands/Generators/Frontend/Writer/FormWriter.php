<?php

namespace Hitocean\Generator\Commands\Generators\Frontend\Writer;

use Hitocean\Generator\Commands\Generators\Config\DTOS\FrontendAbmAttributeDTO;
use Hitocean\Generator\Commands\Generators\FileAdmin;
use Illuminate\Support\Str;

class FormWriter
{
    public static function createFile(string $folder, string $name, array $attributes)
    {
        $directory = static::path($folder, $name);

        $lowerName = Str::lower($name);
        $upperName = Str::ucfirst($name);
        FileAdmin::writeFile(
            'model',
            base_path($directory),
            [
                'upperName' => $upperName,
                'lowerName' => $lowerName,
                'formFields' => static::getFormFields($attributes),
            ],
            base_path('app/Console/Generators/Frontend/Stubs/form.stub')
        );
    }

    private static function path($rootName, $name): string
    {
        $upperName = Str::ucfirst($name);

        return "frontend/$rootName/components/{$upperName}Form/{$upperName}Form.tsx";
    }

    /**
     * @param FrontendAbmAttributeDTO[] $attributes
     * @return string
     * @throws \Exception
     */
    public static function getFormFields(array $attributes): string
    {
        $attrs = "";
        foreach ($attributes as $attribute) {
            $type = $attribute->type !== "string" ? 'type="' . static::fieldType($attribute) . '"' : "";
            $field = '          <Field
            name="' . $attribute->name . '"
            label="' . Str::ucfirst($attribute->translation) . (! $attribute->isOptional ? ' *' : '') . '"
            ' . $type . '
            variant="outlined"
            className="col-span-6"
            component={' . static::componentType($attribute) . '}
            fullWidth
          />';
            $attrs .= $field . "\r\n\t";
        }

        return $attrs;
    }

    private static function fieldType(FrontendAbmAttributeDTO $attribute): string
    {
        return match ($attribute->type) {
            'int', 'float' => 'number',
            'bool' => 'checkbox',
            default => 'text',
        };
    }

    private static function componentType(FrontendAbmAttributeDTO $attribute): string
    {
        return match ($attribute->type) {
            'bool' => 'CheckboxWithLabel',
            default => 'TextField',
        };
    }
}
