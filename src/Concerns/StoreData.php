<?php

namespace ItpassionLtd\Countries\Concerns;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use ItpassionLtd\Countries\Models\CallingCode;
use ItpassionLtd\Countries\Models\Continent;
use ItpassionLtd\Countries\Models\Country;
use ItpassionLtd\Countries\Models\Currency;
use ItpassionLtd\Countries\Models\Nationality;
use ItpassionLtd\Countries\Models\Region;

trait StoreData
{
    /**
     * Store the calling code data in the database.
     *
     * @param string $baseDirectory
     * @return void
     */
    protected function storeCallingCodes(string $baseDirectory):void
    {
        $this->output->write('         Storing calling codes ... ');
        $start = microtime(true);

        $countriesDirectoryName = $baseDirectory.'/src/data/countries/default';
        $countryDirectory = opendir($countriesDirectoryName);
        while($fileName = readdir($countryDirectory)) {
            if($fileName !== '.' && $fileName !== '..' && $fileName !== '_all_countries.json') {
                $jsonString = file_get_contents($countriesDirectoryName.'/'.$fileName);
                $countryJson = json_decode($jsonString, true);
                foreach(($countryJson['dialling']['calling_code'] ?? []) as $countryCallingCode) {
                    CallingCode::updateOrCreate([
                        'calling_code' => '+'.$countryCallingCode
                    ]);
                }
            }
        }
        closedir($countryDirectory);

        $duration = microtime(true) - $start;
        $this->output->writeLn('done ('.$duration.'s)');
    }

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
     * Store the calling code data in the database.
     *
     * @param string $baseDirectory
     * @return void
     */
    protected function storeCountries(string $baseDirectory):void
    {
        $this->output->write('         Storing countries ... ');
        $start = microtime(true);

        $countriesDirectoryName = $baseDirectory.'/src/data/countries/default';
        $countryDirectory = opendir($countriesDirectoryName);
        while($fileName = readdir($countryDirectory)) {
            if($fileName !== '.' && $fileName !== '..' && $fileName !== '_all_countries.json') {
                $jsonString = file_get_contents($countriesDirectoryName.'/'.$fileName);
                $countryJson = json_decode($jsonString, true);
                $nationality = Nationality::whereName($countryJson['demonym'] ?? '')->first();
                $region = Region::whereName($countryJson['geo']['subregion'] ?? '')->first() ?? null;
                if(
                    ($countryJson['iso_3166_1_alpha2'] ?? null) !== null &&
                    ($countryJson['iso_3166_1_alpha3'] ?? null) !== null &&
                    ($countryJson['iso_3166_1_numeric'] ?? null) !== null
                ) {
                    Country::updateOrCreate([
                        'iso_3166_1_alpha2' => $countryJson['iso_3166_1_alpha2'],
                        'iso_3166_1_alpha3' => $countryJson['iso_3166_1_alpha3'],
                        'iso_3166_1_numeric' => $countryJson['iso_3166_1_numeric'],
                    ], [
                        'address_format' => $countryJson['extra']['address_format'] ?? null,
                        'capital' => $countryJson['capital'][0],
                        'flag_path' => public_path('vendor/countries/flags') . '/' . Str::lower($countryJson['iso_3166_1_alpha3']) . '.svg',
                        'landlocked' => $countryJson['geo']['landlocked'] ?? false,
                        'name_common' => $countryJson['name']['common'],
                        'name_official' => $countryJson['name']['official'],
                        'national_destination_code_length' => $countryJson['dialling']['national_destination_code_lengths'][0] ?? 0,
                        'national_number_length' => $countryJson['dialling']['national_number_lengths'][0] ?? 0,
                        'national_prefix' => $countryJson['dialling']['national_prefix'] ?? null,
                        'nationality_id' => $nationality->id ?? null,
                        'region_id' => $region->id ?? null,
                        'uses_postal_code' => $countryJson['geo']['postal_code'] ?? false,
                    ]);
                } else {
                    Log::info('The country in the file "'.$fileName.'" does not have an `iso_3166_1_alpha2` field.');
                }
            }
        }
        closedir($countryDirectory);

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
     * Store the nationalities data in the database.
     *
     * @param string $baseDirectory
     * @return void
     */
    protected function storeNationalities(string $baseDirectory):void
    {
        $this->output->write('         Storing nationalities ... ');
        $start = microtime(true);

        $countriesDirectoryName = $baseDirectory.'/src/data/countries/default';
        $countryDirectory = opendir($countriesDirectoryName);
        while($fileName = readdir($countryDirectory)) {
            if($fileName !== '.' && $fileName !== '..' && $fileName !== '_all_countries.json') {
                $jsonString = file_get_contents($countriesDirectoryName.'/'.$fileName);
                $countryJson = json_decode($jsonString, true);
                if(isset($countryJson['demonym']) && $countryJson['demonym'] !== '') {
                    Nationality::updateOrCreate([
                        'name' => $countryJson['demonym']
                    ]);
                }
            }
        }
        closedir($countryDirectory);

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
        $this->storeCallingCodes($baseDirectory);
        $this->storeContinents();
        $this->storeCurrencies($baseDirectory);
        $this->storeNationalities($baseDirectory);
        $this->storeRegions();

        $this->storeCountries($baseDirectory);
    }
}