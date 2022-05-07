<?php


namespace Hitocean\Generator\Commands\Generators\Backend\Writers;


use Hitocean\Generator\Commands\Generators\Config\DTOS\ModelAttributeDTO;
use Hitocean\Generator\Commands\Generators\FileAdmin;
use Illuminate\Support\Str;

class ResourceHelperTestWriter extends ClassWriter {

    protected static $folder = 'ResourcesHelpers';

    public static function createClassFile($rootName, $className, $attributes)
    {
        $directory = static::path($rootName, $className);
        $folders   = explode('/', $directory);
        unset($folders[count($folders) - 1]);
        $dir = implode('/', $folders);
        static::makeDirectory($dir);
        $testNamespace = static::namespace($rootName, $className);
        $folders       = explode('\\', $testNamespace);
        unset($folders[count($folders) - 1]);

        FileAdmin::writeFile(
            'resource_helper_test',
            base_path($directory),
            [
                'modelNameVar' => ModelWriter::modelNameVar($className),
                'modelImport' => ModelWriter::import($rootName, $className),
                'modelName' => ModelWriter::className($className),
                'className'  => static::className($className),
                'attributes' => static::writeAttributes($attributes, $className)
            ]
        );
    }

    public static function namespace($rootFolder, $name): string
    {
        return "Tests\\ResourceHelper\\".static::rootFolder($rootFolder).'\\'.static::className($name).'';
    }

    public static function folderName($name): string
    {
        return Str::ucfirst($name);
    }

    private static function makeDirectory($directoryName)
    {
        if (!file_exists($directoryName))
            if (!mkdir($concurrentDirectory = base_path($directoryName), 0777, true) && !is_dir($concurrentDirectory)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
            }
    }

    public static function path($rootFolder, $name): string
    {
        return "tests/ResourceHelpers/".static::rootFolder($rootFolder).'/'.static::className($name).'.php';
    }

    public static function className($name): string
    {
        $name = Str::ucfirst($name);

        if (str_contains($name, 'ResourceTestHelper'))
            return $name;
        return $name . 'ResourceTestHelper';
    }

    /**
     * @param ModelAttributeDTO[] $attributes
     * @param string $class_name
     * @return string
     */
    public static function writeAttributes(array $attributes, string $class_name): string
    {
        $model_name_var = ModelWriter::modelNameVar($class_name);
        $attrs = "'id' => $".$model_name_var."->id,\r\n\t";
        foreach ($attributes as $attribute) {
            $attrs .= "'" . Str::snake($attribute->name) . "' => " . "$$model_name_var->{$attribute->name},\r\n\t";
        }
        return $attrs;
    }

}
