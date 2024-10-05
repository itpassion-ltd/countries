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
            __DIR__.'/../database/create_borders_table.php.stub' => database_path('migrations/'.date('Y_m_d_His').'_create_borders_table.php'),
            __DIR__.'/../database/create_calling_codes_table.php.stub' => database_path('migrations/'.date('Y_m_d_His').'_create_calling_codes_table.php'),
            __DIR__.'/../database/create_continents_table.php.stub' => database_path('migrations/'.date('Y_m_d_His').'_create_continents_table.php'),
            __DIR__.'/../database/create_countries_table.php.stub' => database_path('migrations/'.date('Y_m_d_His').'_create_countries_table.php'),
            __DIR__.'/../database/create_currencies_table.php.stub' => database_path('migrations/'.date('Y_m_d_His').'_create_currencies_table.php'),
            __DIR__.'/../database/create_nationalities_table.php.stub' => database_path('migrations/'.date('Y_m_d_His').'_create_nationalities_table.php'),
            __DIR__.'/../database/create_regions_table.php.stub' => database_path('migrations/'.date('Y_m_d_His').'_create_regions_table.php'),
            __DIR__.'/../database/create_subdivisions_table.php.stub' => database_path('migrations/'.date('Y_m_d_His').'_create_subdivisions_table.php'),
        ];

        $migrationsDirectoryName = database_path('migrations');
        $migrationsDirectory = opendir($migrationsDirectoryName);
        while ($file = readdir($migrationsDirectory)) {
            if ($file !== '.' || $file !== '..') {
                if(Str::contains($file, 'borders')) {
                    unset($migrations[__DIR__ . '/../database/create_borders_table.php.stub']);
                } elseif(Str::contains($file, 'calling_codes')) {
                    unset($migrations[__DIR__ . '/../database/create_calling_codes_table.php.stub']);
                } elseif(Str::contains($file, 'continents')) {
                    unset($migrations[__DIR__.'/../database/create_continents_table.php.stub']);
                } elseif(Str::contains($file, 'countries')) {
                    unset($migrations[__DIR__.'/../database/create_countries_table.php.stub']);
                } elseif(Str::contains($file, 'currencies')) {
                    unset($migrations[__DIR__ . '/../database/create_currencies_table.php.stub']);
                } elseif(Str::contains($file, 'nationalities')) {
                    unset($migrations[__DIR__ . '/../database/create_nationalities_table.php.stub']);
                } elseif(Str::contains($file, 'regions')) {
                    unset($migrations[__DIR__.'/../database/create_regions_table.php.stub']);
                } elseif(Str::contains($file, 'subdivisions')) {
                    unset($migrations[__DIR__.'/../database/create_subdivisions_table.php.stub']);
                }
            }
        }
        closedir($migrationsDirectory);

        $this->publishesMigrations($migrations, 'countries-migrations');
        $this->publishes([
            __DIR__.'/../data/public' => public_path('vendor/countries'),
        ], 'countries-assets');
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
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/itpassion-ltd-countries.php', 'itpassion-ltd-countries'
        );
    }
}