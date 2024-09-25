<?php

namespace ItpassionLtd\Countries\Console\Commands\Countries;

use Illuminate\Console\Command;
use ItpassionLtd\Countries\Concerns\DownloadAntonioRibeiroCountries;
use ItpassionLtd\Countries\Concerns\StoreData;
use ItpassionLtd\Countries\Concerns\UnzipRepository;

class Update extends Command
{
    use DownloadAntonioRibeiroCountries, StoreData, UnzipRepository;

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
            $this->components->info('Downloading repository ...');
            $zipFileName = $this->downloadAntonioRibeiroCountries();
            $this->components->info('Extracting repository ...');
            $directory = $this->unzipRepository($zipFileName);
            $this->components->info('Storing data in the database ...');
            $this->
            $this->components->success('Countries information successfully updated.');
            exit(self::SUCCESS);
        } catch(\Exception $exception) {
            $this->components->error('The update failed: "' . $exception->getMessage() . '"');
            exit(self::FAILURE);
        }
    }
}