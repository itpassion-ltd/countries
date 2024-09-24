<?php

namespace ItpassionLtd\Countries;

use Illuminate\Support\ServiceProvider;

class CountriesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'../config/itpassion-ltd-countries.php' => config_path('itpassion-ltd-countries.php'),
        ]);

        $this->publishesMigrations([
            __DIR__.'/../database/create_countries_table.php.stub' => database_path('migrations/'.date('Y_m_d_His').'_create_countries_table.php'),
        ]);
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'../config/itpassion-ltd-countries.php', 'itpassion-ltd-countries'
        );
    }
}