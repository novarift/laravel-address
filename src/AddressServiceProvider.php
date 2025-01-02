<?php

namespace Novarift\Address;

use Novarift\Address\Commands\SeedCountriesCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class AddressServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-address')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigrations([
                'create_countries_table',
                'create_addresses_table',
            ])->hasCommands([
                SeedCountriesCommand::class,
            ]);
    }
}
