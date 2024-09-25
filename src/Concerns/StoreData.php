<?php

namespace ItpassionLtd\Countries\Concerns;

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
        $this->write('Storing currencies ... ');
        $start = microtime(true);

        $duration = microtime(true) - $start;
        $this->writeLn('done ('.$duration.'s)');
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