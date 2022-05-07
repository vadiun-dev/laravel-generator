<?php


namespace Hitocean\Generator\Commands\Generators\Backend;


use Hitocean\Generator\Commands\Generators\Backend\Writers\DTOWriter;
use Illuminate\Console\Command;

class DTOGeneratorCommand extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:dto {rootName} {className}';

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
        $className = $this->argument('className');
        $rootName = $this->argument('rootName');

        DTOWriter::createClassFile($rootName, $className, []);
    }

}
