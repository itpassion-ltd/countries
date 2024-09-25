<?php

namespace ItpassionLtd\Countries\Console\Commands\Countries;

use Illuminate\Console\Command;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'countries:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the countries package by publishing all necessary files into your source code structure.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
    }
}