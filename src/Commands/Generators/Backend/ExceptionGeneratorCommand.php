<?php


namespace Hitocean\Generator\Commands\Generators\Backend;


use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ExceptionGeneratorCommand extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:exception {rootName} {className}';

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
        $className = ucfirst($this->argument('className'));
        $rootName = ucfirst($this->argument('rootName'));

        $directory = 'src/' . $rootName.'/Exceptions/'.$className.'Exception.php';
        FileAdmin::writeFile(
            'exception', base_path($directory),
            ['rootFolder' => $rootName, 'className' => $className.'Exception']
        );
    }

}
