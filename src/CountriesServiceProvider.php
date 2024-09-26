<?php

namespace ItpassionLtd\Countries;

use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\ServiceProvider;
use ItpassionLtd\Countries\Console\Commands\Countries\Install;
use ItpassionLtd\Countries\Console\Commands\Countries\Update;

class CountriesServiceProvider extends ServiceProvider
{
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

        $this->publishesMigrations([
            __DIR__.'/../database/create_countries_table.php.stub' => database_path('migrations/'.date('Y_m_d_His').'_create_countries_table.php'),
        ], 'countries-migrations');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/itpassion-ltd-countries.php', 'itpassion-ltd-countries'
        );
    }
}