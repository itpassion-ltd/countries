<?php

namespace ItpassionLtd\Countries\Concerns;

use Exception;
use ZipArchive;

trait UnzipRepository
{
    /**
     * Unzip the given file, and return the name of the directory that was created.
     *
     * @param string $zipFileName
     * @return string
     * @throws Exception
     */
    protected function unzipRepository(string $zipFileName): string
    {
        $zipArchive = new ZipArchive;
        if($zipArchive->open($zipFileName) === true) {
            $path = storage_path('app/private/countries');
            $zipArchive->extractTo($path);
            $zipArchive->close();
            return $path;
        } else {
            throw new Exception('Cannot extract zip file "'.$zipFileName.'"');
        }
    }
}
