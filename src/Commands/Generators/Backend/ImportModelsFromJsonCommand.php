<?php


namespace Hitocean\Generator\Commands\Generators\Backend;


use Hitocean\Generator\Commands\Generators\Config\ConfigFinder;
use Hitocean\Generator\Commands\Generators\Config\DTOS\ActionDTO;
use Hitocean\Generator\Commands\Generators\Config\DTOS\ConfigDTO;
use Hitocean\Generator\Commands\Generators\Backend\Writers\ActionWriter;
use Hitocean\Generator\Commands\Generators\Backend\Writers\ModelWriter;
use Illuminate\Console\Command;

class ImportModelsFromJsonCommand extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:models';

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

        /** @var ConfigDTO[] $configs */
        $configs = ConfigFinder::getModels();
        foreach ($configs as $config) {
            $this->call('make:rootDirectory', ['name' => $config->folder]);
            $this->info('Importando modelo '. $config->modelName);
            ModelWriter::createClassFile($config->folder, $config->modelName, $config->tableName, $config->attributes, true, true, $config->has_abm);
        }
    }
}
