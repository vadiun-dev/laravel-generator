<?php

namespace Hitocean\Generator\Commands\Generators\Backend;

use Hitocean\Generator\Commands\Generators\Backend\Writers\MigrationWriter;
use Hitocean\Generator\Commands\Generators\Backend\Writers\ModelWriter;
use Illuminate\Console\Command;

class ModelGeneratorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:model {rootName} {className} {table?} {--hasMigration} {--hasFactory}';

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
        $tableName = $this->argument('table') ?? MigrationWriter::tableName($className);
        $hasMigration = $this->option('hasMigration');
        $hasFactory = $this->option('hasFactory');
        ModelWriter::createClassFile($rootName, $className, $tableName, [], $hasMigration, $hasFactory);
    }
}
