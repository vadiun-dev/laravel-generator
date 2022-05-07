<?php


namespace Hitocean\Generator\Commands\Generators\Backend;


use Hitocean\Generator\Commands\Generators\Config\DTOS\ModelAttributeDTO;
use Hitocean\Generator\Commands\Generators\Backend\Writers\ActionWriter;
use Illuminate\Console\Command;

class ActionGeneratorCommand extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:action {rootName} {className} {routeMethod} {routeName} {resource?} {--r|request} {--d|dto} {--ta|testAction}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make request';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function handle()
    {
        $className    = $this->argument('className');
        $rootName     = $this->argument('rootName');
        $hasDto       = $this->option('dto');
        $hasRequest   = $this->option('request');
        $hasActioTest = $this->option('testAction');
        $resource     = $this->argument('resource');
        $routeMethod  = $this->argument('routeMethod');
        $routeName    = $this->argument('routeName');

        ActionWriter::createClassFile(
            $rootName, $className, $this->getAttributes(), $hasDto, $hasRequest, $hasActioTest, $routeMethod,
            $routeName, $resource
        );

    }

    private function getAttributes(): array
    {
        return $this->askForAttributes();
    }

    private function askForAttributes(): array
    {
        $attributes = [];
        $ask        = 1;
        while ($ask) {
            $attribue = $this->ask(
                'Insert a new field. ej: name:type posible types: string, int, float, file, dto, dtoArray, array'
            );
            $aux      = explode(':', $attribue);

            if (count($aux) > 2)
                $this->info("Incorrect field"); else if (!in_array(
                $aux[1], ['string', 'int', 'float', 'file', 'dto', 'dtoArray', 'array']
            ))
                $this->info("Incorrect field"); else
                $attributes[] = $this->getAttribute($aux[0], $aux[1]);

        }
        return $attributes;
    }

    private function getAttribute($name, $type): ModelAttributeDTO
    {
        switch ($type) {
            case 'string':
                return new ModelAttributeDTO(name: $name, type: 'string');
            case 'int':
                return new ModelAttributeDTO(name: $name, type: 'int');
            case 'float':
                return new ModelAttributeDTO(name: $name, type: 'float');
            case 'file':
                return new ModelAttributeDTO(name: $name, type: 'file');
            case 'dto':
                return new ModelAttributeDTO(name: $name, type: 'dto');
            case 'array':
                return new ModelAttributeDTO(name: $name, type: 'array');
            case 'dtoArray':
                return new ModelAttributeDTO(name: $name, type: 'array');
            default:
                return new ModelAttributeDTO(name: $name, type: 'string');
        }
    }

    private function attributesFromJson(): array
    {
        $attributes = json_decode($this->argument('jsonAttributes'));
        $atts       = [];
        foreach ($attributes as $attribute)
            $atts[] = new ModelAttributeDTO(name: $attribute->name, type: $attribute->type);
        return $atts;
    }


}
