<?php

namespace ItpassionLtd\Countries\Concerns;

use ItpassionLtd\Countries\Models\Currency;

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
                    'minor_name' => $currencyJson['units']['minor']['name'],
                    'minor_symbol' => $currencyJson['units']['minor']['symbol'],
                    'minor_unit' => (float) ($currencyJson['units']['minor']['majorValue'] !== '' ? $currencyJson['units']['minor']['majorValue'] : 0.00),
                    'major_name' => $currencyJson['units']['major']['name'],
                    'major_symbol' => $currencyJson['units']['major']['symbol'],
                    'numeric' => (string) $currencyJson['iso']['number'],
                ]);
            }
        }
        closedir($currenciesDirectory);

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
    }
}