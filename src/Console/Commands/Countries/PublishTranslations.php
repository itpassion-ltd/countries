<?php

namespace ItpassionLtd\Countries\Console\Commands\Countries;

use Illuminate\Console\Command;

class PublishTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'countries:publish-translations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create and publish the translation files.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
    }
}