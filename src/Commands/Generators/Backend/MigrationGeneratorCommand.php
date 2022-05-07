<?php


namespace Hitocean\Generator\Commands\Generators\Backend;


use Hitocean\Generator\Commands\Generators\Backend\Writers\MigrationWriter;
use Illuminate\Console\Command;

class MigrationGeneratorCommand extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:migration {rootName} {className}';

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

        MigrationWriter::createClassFile($rootName, $className, []);
    }
}
