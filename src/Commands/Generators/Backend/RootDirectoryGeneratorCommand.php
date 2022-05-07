<?php


namespace Hitocean\Generator\Commands\Generators\Backend;


use Illuminate\Console\Command;

class RootDirectoryGeneratorCommand extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:rootDirectory {name}';

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
        $name          = ucfirst($this->argument('name'));
        $rootDirectory = "src/$name";
        $this->makeDirectory($rootDirectory);
        $this->makeDirectory($rootDirectory . "/Actions");
        $this->makeDirectory($rootDirectory . "/Actions/DTOS");

        $this->makeDirectory($rootDirectory . "/Exceptions");
        $this->makeDirectory($rootDirectory . "/Requests");
        $this->makeDirectory($rootDirectory . "/Models");
        $this->makeDirectory($rootDirectory . "/Resources");


    }

    private function makeDirectory($directoryName)
    {
        if (!file_exists(base_path($directoryName)))
            if (!mkdir($concurrentDirectory = base_path($directoryName), 0777, true) && !is_dir($concurrentDirectory)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
            }
    }
}
