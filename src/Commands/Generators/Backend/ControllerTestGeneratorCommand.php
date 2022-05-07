<?php


namespace Hitocean\Generator\Commands\Generators\Backend;


use Hitocean\Generator\Commands\Generators\Backend\Writers\ActionWriter;
use Hitocean\Generator\Commands\Generators\Backend\Writers\ControllerTestWriter;
use Hitocean\Generator\Commands\Generators\Backend\Writers\RequestWriter;
use Illuminate\Console\Command;

class ControllerTestGeneratorCommand extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:controllertest {rootName} {className} {routeMethod} {route}';

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
        $className   = $this->argument('className');
        $rootName    = $this->argument('rootName');
        $route       = $this->argument('route');
        $routeMethod = $this->argument('routeMethod');

        ControllerTestWriter::createClassFile($rootName, $className, $route, $routeMethod);
    }


}
