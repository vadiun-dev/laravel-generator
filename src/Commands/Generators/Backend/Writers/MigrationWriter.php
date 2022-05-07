<?php


namespace Hitocean\Generator\Commands\Generators\Backend\Writers;


use Hitocean\Generator\Commands\Generators\Config\DTOS\ModelAttributeDTO;
use Hitocean\Generator\Commands\Generators\FileAdmin;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class MigrationWriter extends ClassWriter {

    public static function namespace($rootFolder, $name): string
    {
        return "Database\\Factories\\" . static::className($name);
    }

    public static function className($name): string
    {
        $name = Str::ucfirst($name);

        if (str_contains($name, 'Tables'))
            return $name;
        $pluralClassName = Str::pluralStudly($name);
        return 'Create'.Str::ucfirst(Str::camel($pluralClassName)) . 'Tables';
    }

    public static function createClassFile(string $rootName, string $className, array $attributes, string $table_name)
    {
        $directory = static::path($rootName, $className);
        if(static::fileExists('create_'.static::tableName($className) . '_tables'))
            return;
        FileAdmin::writeFile(
            'migration', base_path($directory), [
                'className'  => static::className($className),
                'tableName'  => $table_name,
                'attributes' => static::writeAttributes($attributes)
            ]
        );
    }

    private static function fileExists($fileName): bool
    {
        $iterator = new \DirectoryIterator(base_path('/database/migrations'));
        foreach($iterator as $file)
            if(Str::contains($file->getFilename(), $fileName))
                return true;
        return false;
    }

    public static function path($rootFolder, $name): string
    {
        $date = Carbon::now()->format('Y_m_d_his');
        $fileName = $date.'_create_'.static::tableName($name) . '_tables';
        return "database/migrations/" . $fileName . '.php';
    }

    public static function tableName($name)
    {
        $pluralClassName = Str::pluralStudly($name);
        return Str::snake($pluralClassName);
    }

    /**
     * @param ModelAttributeDTO[] $attributes
     * @return string
     * @throws \Exception
     */
    public static function writeAttributes(array $attributes): string
    {
        $attrs = '';
        foreach ($attributes as $attribute) {
            if (static::canBeNull($attribute->type))
                $attrs .= '$table->' . static::attributeType(
                        $attribute->type
                    ) . "('" . $attribute->name . "')->nullable();\r\n\t"; else
                $attrs .= '$table->' . static::attributeType($attribute->type) . "('" . $attribute->name . "');\r\n\t";
        }
        return $attrs;
    }

    private static function canBeNull(string $type): string
    {
        return str_contains($type, '?');
    }

    private static function attributeType(string $type): string
    {
        if (str_contains($type, '?')) {
            $type     = Str::remove('?', $type);
        }
        if (!in_array($type, ['string', 'int', 'float', 'date', 'bool', 'datetime']))
            throw new \Exception('el tipo ' . $type . ' no puede ser procesado');

        switch ($type) {
            case 'int':
                return 'integer';
            case 'float':
                return 'double';
            case 'bool':
                return 'boolean';
            default:
                return $type;
        }
    }

}
