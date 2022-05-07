<?php


namespace Hitocean\Generator\Commands\Generators\Frontend;


use Hitocean\Generator\Commands\Generators\Config\ConfigFinder;
use Hitocean\Generator\Commands\Generators\Config\DTOS\ActionDTO;
use Hitocean\Generator\Commands\Generators\Config\DTOS\ConfigDTO;
use Hitocean\Generator\Commands\Generators\Config\DTOS\FrontendAbmDTO;
use Hitocean\Generator\Commands\Generators\Frontend\Writer\CreateWriter;
use Hitocean\Generator\Commands\Generators\Frontend\Writer\EditWriter;
use Hitocean\Generator\Commands\Generators\Frontend\Writer\FormInitialValueWriter;
use Hitocean\Generator\Commands\Generators\Frontend\Writer\FormTypeWriter;
use Hitocean\Generator\Commands\Generators\Frontend\Writer\FormValidationWriter;
use Hitocean\Generator\Commands\Generators\Frontend\Writer\FormWriter;
use Hitocean\Generator\Commands\Generators\Frontend\Writer\ModelWriter;
use Hitocean\Generator\Commands\Generators\Frontend\Writer\PageCrudWriter;
use Hitocean\Generator\Commands\Generators\Frontend\Writer\ServiceWriter;
use Hitocean\Generator\Commands\Generators\Frontend\Writer\TableWriter;
use Hitocean\Generator\Commands\Generators\Backend\Writers\ActionWriter;
use Illuminate\Console\Command;

class ImportAbmsFromJsonCommand extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:frontendAbms';

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

        /** @var FrontendAbmDTO[] $configs */
        $configs = ConfigFinder::getFrontendAbms();
        foreach ($configs as $config) {
            $this->call('make:frontendRootDirectory', ['name' => $config->folder]);
            $this->info('Importando abm '. $config->name);
            ModelWriter::createFile($config->folder, $config->name, $config->attributes);
            FormInitialValueWriter::createFile($config->folder, $config->name, $config->attributes);
            FormTypeWriter::createFile($config->folder, $config->name, $config->attributes);
            FormValidationWriter::createFile($config->folder, $config->name, $config->attributes);
            FormWriter::createFile($config->folder, $config->name, $config->attributes);
            CreateWriter::createFile($config->folder, $config->name,  $config->translation);
            EditWriter::createFile($config->folder, $config->name, $config->translation);
            ServiceWriter::createFile($config->folder, $config->name, $config->route);
            PageCrudWriter::createFile($config->folder, $config->name, $config->translation);
            TableWriter::createFile($config->folder, $config->name, $config->attributes, $config->translation);
        }
    }
}
