<?php

namespace Hitocean\Generator\Commands;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Hitocean\Generator\Commands\Commands\GeneratorCommand;

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
            ->hasCommand(GeneratorCommand::class);
    }
}
