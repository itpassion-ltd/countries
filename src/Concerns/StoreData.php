<?php

namespace ItpassionLtd\Countries\Concerns;

use Illuminate\Support\Facades\Log;
use ItpassionLtd\Countries\Models\Continent;
use ItpassionLtd\Countries\Models\Currency;
use ItpassionLtd\Countries\Models\Region;

trait StoreData
{
    /**
     * Store the continent data in the database.
     *
     * @return void
     */
    protected function storeContinents():void
    {
        $this->output->write('         Storing continents ... ');
        $start = microtime(true);

        $allContinentsStr = file_get_contents(__DIR__.'/../../data/all_continents.json');
        $allContinentsJson = json_decode($allContinentsStr, true);
        foreach($allContinentsJson as $continentJson) {
            Continent::updateOrCreate([
                'name' => $continentJson['name'],
            ]);
        }

        $duration = microtime(true) - $start;
        $this->output->writeLn('done ('.$duration.'s)');
    }

    /**
     * Store the currency data in the database.
     *
     * @param string $baseDirectory
     * @return void
     */
    protected function storeCurrencies(string $baseDirectory):void
    {
        $this->output->write('         Storing currencies ... ');
        $start = microtime(true);

        $currenciesDirectoryName = $baseDirectory.'/src/data/currencies/default';
        $currenciesDirectory = opendir($currenciesDirectoryName);
        while($fileName = readdir($currenciesDirectory)) {
            if($fileName !== '.' && $fileName !== '..') {
                $jsonString = file_get_contents($currenciesDirectoryName.'/'.$fileName);
                $currencyJson = json_decode($jsonString, true);
                Currency::updateOrCreate([
                    'alpha_3' => $currencyJson['iso']['code'],
                    'numeric' => (string) $currencyJson['iso']['number'],
                ], [
                    'minor_name' => $currencyJson['units']['minor']['name'],
                    'minor_symbol' => $currencyJson['units']['minor']['symbol'],
                    'minor_unit' => (float) ($currencyJson['units']['minor']['majorValue'] !== '' ? $currencyJson['units']['minor']['majorValue'] : 0.00),
                    'major_name' => $currencyJson['units']['major']['name'],
                    'major_symbol' => $currencyJson['units']['major']['symbol'],
                ]);
            }
        }
        closedir($currenciesDirectory);

        $duration = microtime(true) - $start;
        $this->output->writeLn('done ('.$duration.'s)');
    }

    /**
     * Store the region data in the database.
     *
     * @return void
     */
    protected function storeRegions():void
    {
        $this->output->write('         Storing regions ... ');
        $start = microtime(true);

        $allRegionsStr = file_get_contents(__DIR__.'/../../data/all_regions.json');
        $allRegionsJson = json_decode($allRegionsStr, true);
        foreach($allRegionsJson as $regionJson) {
            $parentRegion = null;
            $parentRegionName = $regionJson['parent_region_name'] ?? null;
            if($parentRegionName) {
                $parentRegion = Region::whereName($parentRegionName)->first();
            }
            Region::updateOrCreate([
                'name' => $regionJson['name'],
            ], [
                'parent_region_id' => $parentRegion->id ?? null,
                'un_numeric' => $regionJson['un_numeric'] ?? null,
            ]);
        }

        $duration = microtime(true) - $start;
        $this->output->writeLn('done ('.$duration.'s)');
    }

    /**
     * Store all the data in the database.
     *
     * @param string $baseDirectory
     * @return void
     */
    protected function storeData(string $baseDirectory): void
    {
        $this->storeContinents();
        $this->storeCurrencies($baseDirectory);
        $this->storeRegions();
    }
}