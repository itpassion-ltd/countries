<?php

namespace ItpassionLtd\Countries;

use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use ItpassionLtd\Countries\Console\Commands\Countries\Install;
use ItpassionLtd\Countries\Console\Commands\Countries\Update;

class CountriesServiceProvider extends ServiceProvider
{
    protected function advertiseUnpublishedMigrations(): void
    {
        $migrations = [
            __DIR__.'/../database/create_continents_table.php.stub' => database_path('migrations/'.date('Y_m_d_His').'_create_continents_table.php'),
            __DIR__.'/../database/create_currencies_table.php.stub' => database_path('migrations/'.date('Y_m_d_His').'_create_currencies_table.php'),
            __DIR__.'/../database/create_regions_table.php.stub' => database_path('migrations/'.date('Y_m_d_His').'_create_regionss_table.php'),
        ];

        $migrationsDirectoryName = database_path('migrations');
        $migrationsDirectory = opendir($migrationsDirectoryName);
        while ($file = readdir($migrationsDirectory)) {
            if ($file !== '.' || $file !== '..') {
                if(Str::contains($file, 'continents')) {
                    unset($migrations[__DIR__.'/../database/create_continents_table.php.stub']);
                } elseif(Str::contains($file, 'currencies')) {
                    unset($migrations[__DIR__ . '/../database/create_currencies_table.php.stub']);
                } elseif(Str::contains($file, 'regions')) {
                    unset($migrations[__DIR__.'/../database/create_regions_table.php.stub']);
                }
            }
        }
    }

    public function boot(): void
    {
        if($this->app->runningInConsole()){
            AboutCommand::add('ITPassion Ltd Countries', fn () => ['Version 1.0.0']);
            $this->commands([
                Install::class,
                Update::class,
            ]);
        }


        $this->publishes([
            __DIR__.'/../config/itpassion-ltd-countries.php' => config_path('itpassion-ltd-countries.php'),
        ], 'countries-config');

        $this->advertiseUnpublishedMigrations();

        $this->publishesMigrations([
            __DIR__.'/../database/create_continents_table.php.stub' => database_path('migrations/'.date('Y_m_d_His').'_create_continents_table.php'),
            __DIR__.'/../database/create_currencies_table.php.stub' => database_path('migrations/'.date('Y_m_d_His').'_create_currencies_table.php'),
            __DIR__.'/../database/create_regions_table.php.stub' => database_path('migrations/'.date('Y_m_d_His').'_create_regionss_table.php'),
        ], 'countries-migrations');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/itpassion-ltd-countries.php', 'itpassion-ltd-countries'
        );
    }
}