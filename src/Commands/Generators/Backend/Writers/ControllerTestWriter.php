<?php


namespace Hitocean\Generator\Commands\Generators\Backend\Writers;


use Hitocean\Generator\Commands\Generators\Config\DTOS\ActionAttributeDTO;
use Hitocean\Generator\Commands\Generators\FileAdmin;
use Illuminate\Support\Str;

class ControllerTestWriter extends ClassWriter {


    public static function createClassFile($rootName,
                                           $className,
                                           $routeMethod,
                                           $routeName,
                                           array $attributes,
                                           bool $has_dto
    ): void {
        $directory = static::path($rootName, $className);
        $folders   = explode('/', $directory);
        unset($folders[count($folders) - 1]);
        $dir = implode('/', $folders);
        static::makeDirectory($dir);
        $testNamespace = static::namespace($rootName, $className);
        $folders       = explode('\\', $testNamespace);
        unset($folders[count($folders) - 1]);
        $testNamespace = implode('\\', $folders);

        FileAdmin::writeFile(
            'controllertest',
            base_path($directory),
            [
                'actionImport'     => ActionWriter::import($rootName, $className),
                'requestImport'    => RequestWriter::import($rootName, $className),
                'testNamespace'    => $testNamespace,
                'dtoImport'        => $has_dto ? DTOWriter::import($rootName, $className) : "/r/n/t",
                'routeMethod'      => $routeMethod,
                'routeName'        => 'api/' . $routeName,
                'actionClassName'  => ActionWriter::className($className),
                'requestClassName' => RequestWriter::className($className),
                'className'        => static::className($className),
                'test_name'        => static::testName($className),
                'example_test'     => static::exampleTest($className, $routeMethod, $routeName, $attributes, $has_dto),
                'gate_name'        => GateWriter::gateName($className)
            ]
        );
    }

    public static function path($rootFolder, $name): string
    {
        return "tests/Actions/" . static::rootFolder($rootFolder) . '/' . static::folderName(
                $name
            ) . '/' . static::className($name) . '.php';
    }

    public static function folderName($name): string
    {
        return Str::ucfirst($name);
    }

    public static function className($name): string
    {
        $name = Str::ucfirst($name);

        if (str_contains($name, 'ControllerTest'))
            return $name;
        return $name . 'ControllerTest';
    }

    private static function makeDirectory($directoryName)
    {
        if (!file_exists($directoryName))
            if (!mkdir($concurrentDirectory = base_path($directoryName), 0777, true) && !is_dir($concurrentDirectory)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
            }
    }

    public static function namespace($rootFolder, $name): string
    {
        return "Tests\\Actions\\" . static::rootFolder($rootFolder) . '\\' . static::folderName(
                $name
            ) . '\\' . static::className($name) . '';
    }

    private static function testName(string $class_name): string
    {
        return Str::snake($class_name);
    }

    /**
     * @param string $class_name
     * @param string $route_method
     * @param string $route_name
     * @param ActionAttributeDTO[] $attributes
     * @return string
     */
    private static function exampleTest(string $class_name,
                                        string $route_method,
                                        string $route_name,
                                        array $attributes,
                                        bool $has_dto
    ): string {
        $action_class_name = ActionWriter::className($class_name);
        $attr              = '';
        if ($has_dto) {
            $dto  = DTOWriter::className($class_name);
            $attr .= "\$data = [\r\n\t";
            $attr .= static::writeAttributes($attributes);
            $attr .= "];\r\n\t";
        } else
            $dto = 'null';
        switch ($route_method) {
            case 'get':
                return '$this->basicGetAssert(' . $action_class_name . '::class, "' . 'api/'.$route_name . '", $rol, $expectedJson);';
            case 'post':
                return $attr . '$this->basicPostAssert($data, ' . $action_class_name . '::class, ' . $dto . '::class, "' . 'api/'.$route_name . '", $rol, $expectedJson, $returnFromAction);';
            case 'put':
                return $attr . '$this->basicPutAssert($data, ' . $action_class_name . '::class, ' . $dto . '::class, "' . 'api/'.$route_name . '", $rol, $expectedJson, $returnFromAction);';
            case 'delete':
                return $attr . '$this->basicDeleteAssert($data, ' . $action_class_name . '::class, ' . $dto . '::class, "' . 'api/'.$route_name . '", $rol);';
            default:
                return "";
        }

    }

    /**
     * @param ActionAttributeDTO[] $attributes
     * @return string
     */
    public static function writeAttributes(array $attributes): string
    {
        $attrs = "";
        foreach ($attributes as $attribute) {
            if ($attribute->isDto())
                $attrs .= "'" . $attribute->name . "' => [" . static::writeAttributes(
                        $attribute->attributes
                    ) . "],\r\n\t";
            else
                $attrs .= "'" . $attribute->name . "' => " . $attribute->fakerValue() . ",\r\n\t";
        }
        return $attrs;
    }

}
