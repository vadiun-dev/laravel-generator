<?php

namespace Hitocean\Generator\Commands\Generators\Backend;

use Hitocean\Generator\Commands\Generators\Backend\Writers\ActionWriter;
use Hitocean\Generator\Commands\Generators\Config\ConfigFinder;
use Hitocean\Generator\Commands\Generators\Config\DTOS\ActionDTO;
use Illuminate\Console\Command;

class ImportActionsFromJsonCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:actions';

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
        /** @var ActionDTO[] $actions */
        $actions = ConfigFinder::getActions();
        foreach ($actions as $action) {
            $this->info('Creando accion '. $action->name);
            ActionWriter::createClassFile(
                $action->folder,
                $action->name,
                $action->attributes,
                count($action->attributes) > 0,
                true,
                $action->actionTest,
                $action->routeMethod,
                $action->route,
                $action->resource
            );
        }
    }
}
