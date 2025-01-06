<?php

namespace Novarift\Address;

use Novarift\Address\Commands\SeedCommand;
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
                'create_states_table',
                'create_post_offices_table',
                'create_districts_table',
                'create_mukims_table',
                'create_addresses_table',
            ])->hasCommands([
                SeedCommand::class,
            ]);
    }
}
