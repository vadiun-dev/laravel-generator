<?php

namespace Hitocean\Generator;

use Hitocean\Generator\Commands\Generators\Backend\ImportActionsFromJsonCommand;
use Hitocean\Generator\Commands\Generators\Backend\ImportModelsFromJsonCommand;
use Hitocean\Generator\Commands\Generators\Backend\RootDirectoryGeneratorCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Hitocean\Generator\Commands\GeneratorCommand;

class GeneratorServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-generator')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-generator_table')
            ->hasCommand(ImportActionsFromJsonCommand::class)
            ->hasCommand(RootDirectoryGeneratorCommand::class)
            ->hasCommand(ImportModelsFromJsonCommand::class);
    }
}
