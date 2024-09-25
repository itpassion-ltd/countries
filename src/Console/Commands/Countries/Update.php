<?php

namespace ItpassionLtd\Countries\Console\Commands\Countries;

use Illuminate\Console\Command;
use ItpassionLtd\Countries\Concerns\DownloadAntonioRibeiroCountries;

class Update extends Command
{
    use DownloadAntonioRibeiroCountries;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'countries:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all country and related data.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->downloadAntonioRibeiroCountries();
            exit(self::SUCCESS);
        } catch(\Exception $exception) {
            $this->components->error('The update failed: "' . $exception->getMessage() . '"');
            exit(self::FAILURE);
        }
    }
}