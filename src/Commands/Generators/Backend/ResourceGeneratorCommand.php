<?php

namespace Hitocean\Generator\Commands\Generators\Backend;

use Hitocean\Generator\Commands\Generators\Backend\Writers\ResourceWriter;
use Illuminate\Console\Command;

class ResourceGeneratorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:resource {rootName} {className}';

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

        ResourceWriter::createClassFile($rootName, $className, []);
    }
}
