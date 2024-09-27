<?php

namespace ItpassionLtd\Countries\Concerns;

use ItpassionLtd\Countries\Models\Currency;
use ItpassionLtd\Countries\Models\Region;

trait StoreData
{
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
     * @param string $baseDirectory
     * @return void
     */
    protected function storeRegions(string $baseDirectory):void
    {
        $this->output->write('         Storing regions ... ');
        $start = microtime(true);

        $worldRegion = Region::updateOrCreate([
            'name' => 'World',
        ], [
            'parent_region_id' => null,
            'un_numeric' => '001',
        ]);

        $countriesDirectoryName = $baseDirectory.'/src/data/countries/default';
        $countriesDirectory = opendir($countriesDirectoryName);
        while($fileName = readdir($countriesDirectory)) {
            if($fileName === '.' && $fileName === '..') {
                continue;
            }

            $jsonString = file_get_contents($countriesDirectoryName.'/'.$fileName);
            $countryJson = json_decode($jsonString, true);

            $intermediateRegion = null;
            $region = null;
            $subRegion = null;
            $theaterRegion = null;

            $theaterRegionStr = $countryJson['geo']['world_region'];
            if($theaterRegionStr !== '' && $theaterRegionStr !== null) {
                $theaterRegion = Region::updateOrCreate([
                    'name' => $theaterRegionStr,
                    'parent_region_id' => $worldRegion->id,
                    'un_numeric' => null,
                ]);
            }

            $regionStr = $countryJson['geo']['region'];
            $regionCodeStr = $countryJson['geo']['region_code'] ?? null;
            if($regionStr !== '' && $regionStr !== null) {
                $region = Region::updateOrCreate([
                    'name' => $regionStr,
                ], [
                    'parent_region_id' => $theaterRegion !== null ? $theaterRegion->id : $worldRegion->id,
                    'un_numeric' => $regionCodeStr,
                ]);
            }

            $intermediateRegionStr = $countryJson['geo']['region_wb'];
            if($intermediateRegionStr !== '' && $intermediateRegionStr !== null) {
                $intermediateRegion = Region::create([
                    'name' => $intermediateRegionStr,
                ], [
                    'parent_region_id' => $region !== null ? $region->id : ($theaterRegion !== null ? $theaterRegion->id : $worldRegion->id),
                    'un_numeric' => null,
                ]);
            }

            $subRegionStr = $countryJson['geo']['subregion'];
            $subRegionCodeStr = $countryJson['geo']['subregion_code'] ?? null;
            if($subRegionStr !== '' && $subRegionStr !== null) {
                $subRegion = Region::updateOrCreate([
                    'name' => $subRegionStr,
                ], [
                    'parent_region_id' => $intermediateRegion !== null ? $intermediateRegion->id :
                        ($region !== null ? $region->id : ($theaterRegion !== null ? $theaterRegion->id : $worldRegion->id)),
                    'un_numeric' => $subRegionCodeStr,
                ]);
            }
        }
        closedir($countriesDirectory);

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
        $this->storeCurrencies($baseDirectory);
        $this->storeRegions($baseDirectory);
    }
}