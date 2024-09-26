<?php

namespace ItpassionLtd\Countries\Concerns;

use Exception;
use ZipArchive;

trait UnzipRepository
{
    /**
     * Find the child directory inside the $basePath.
     *
     * @param string $basePath
     * @return string
     * @throws Exception
     */
    protected function findContainedDirectory(string $basePath): string
    {
        $dir = opendir($basePath);
        while($childDirectory = readdir($dir)) {
            if (!($childDirectory == '.' || $childDirectory == '..')) {
                return $basePath.'/'.$childDirectory;
            }
        }

        throw new Exception('Cannot find the child directory into which the repository was extracted.');
    }

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
            return $this->findContainedDirectory($path);
        } else {
            throw new Exception('Cannot extract zip file "'.$zipFileName.'"');
        }
    }
}
