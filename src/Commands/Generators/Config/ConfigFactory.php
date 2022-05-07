<?php


namespace Hitocean\Generator\Commands\Generators\Config;


use Hitocean\Generator\Commands\Generators\Config\DTOS\ActionDTO;
use Hitocean\Generator\Commands\Generators\Config\DTOS\ConfigDTO;
use Hitocean\Generator\Commands\Generators\Config\DTOS\FrontendAbmDTO;
use Illuminate\Support\Str;

class ConfigFactory {

    public static function makeModel($json_data)
    {
        return new ConfigDTO(
            modelName
            :
            $json_data->modelName,
            folder
            :
            $json_data->folder,
            attributes
            :
            array_map(fn ($atr) => ['type' => $atr->type, 'name' => $atr->name], $json_data->attributes),
            tableName
            :
            $json_data->tableName,
            has_abm: property_exists($json_data, 'abm') ? $json_data->abm : false
        );
    }

    public static function makeAction($json_data)
    {
        return new ActionDTO(static::mapAction($json_data));
    }

    private static function mapAction($json_data): array
    {

        return [
            'folder'         => $json_data->folder,
            'name'           => $json_data->name,
            'controllerTest' => $json_data->controllerTest,
            'actionTest'     => $json_data->actionTest,
            'route'          => $json_data->routeName,
            'routeMethod'    => $json_data->routeMethod,
            'roles'          => $json_data->roles,
            'attributes'     => array_map(
                fn ($atr) => [
                    'type'       => $atr->type,
                    'name'       => $atr->name,
                    'attributes' => property_exists($atr, 'attributes') ? static::mapActionAttribute(
                        $atr->attributes
                    ) : []
                ],
                $json_data->attributes
            ),
        ];

    }

    private static function mapActionAttribute($attributes): array
    {
        $attrs = [];
        foreach ($attributes as $attribute) {
            $attrs[] = [
                'name'       => $attribute->name,
                'type'       => $attribute->type,
                'attributes' => property_exists($attribute, 'attributes') ? static::mapActionAttribute(
                    $attribute->attributes
                ) : []
            ];
        }
        return $attrs;
    }

    public static function makeFrontendAbm($json_data)
    {
        return new FrontendAbmDTO(
            [
                'folder'     => $json_data->folder,
                'name'       => $json_data->name,
                'route'      => $json_data->route,
                'attributes' => static::mapFrontedAbmAttributes($json_data->attributes),
                'translation' => $json_data->translation
            ]
        );
    }

    private static function mapFrontedAbmAttributes(array $attributes)
    {
        $attrs = [];
        foreach ($attributes as $attribute)
        {
            $isOptional = false;
            $type = $attribute->type;
            if(str_contains($type, '?'))
            {
                $type = Str::remove('?', $type);
                $isOptional = true;
            }
            $attrs[] = ['type' => $type, 'name' => $attribute->name, 'isOptional' => $isOptional, 'translation' => $attribute->translation];
        }
        return $attrs;
    }

}
