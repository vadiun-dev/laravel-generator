<?php

namespace Hitocean\Generator\Commands\Generators\Frontend;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class RootDirectoryGeneratorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:frontendRootDirectory {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make root directory inside src folder';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = ucfirst($this->argument('name'));
        $rootDirectory = "frontend/$name";
        $this->makeDirectory($rootDirectory);
        $this->makeDirectory($rootDirectory . "/components");
        $this->makeDirectory($rootDirectory . "/components/".Str::ucfirst($name).'Form');
        $this->makeDirectory($rootDirectory . "/models");
        $this->makeDirectory($rootDirectory . "/pages");
        $this->makeDirectory($rootDirectory . "/services");
    }

    private function makeDirectory($directoryName)
    {
        if (! file_exists(base_path($directoryName))) {
            if (! mkdir($concurrentDirectory = base_path($directoryName), 0777, true) && ! is_dir($concurrentDirectory)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
            }
        }
    }
}
