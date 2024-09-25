<?php

namespace ItpassionLtd\Countries\Concerns;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait DownloadAntonioRibeiroCountries
{
    /**
     * Download the latest release file in the antonioribeiro/countries GitHub repository.
     *
     * @return string
     * @throws RequestException
     */
    protected function downloadAntonioRibeiroCountries(): string
    {
        $zipballUrl = $this->getZipBallUrl();
        return $this->downloadZipBall($zipballUrl);
    }

    /**
     * Download the given url in chunks of the given size and return the name of the file created.
     *
     * @param string $url
     * @param int $chunkSize
     * @return string
     */
    protected function downloadChunks(string $url, int $chunkSize = 262144): string
    {
        $zipFileName = storage_path('app/private/countries.zip');
        $zipFile = fopen($zipFileName, 'w');
        $urlStream = fopen($url, 'r');
        while (!feof($urlStream)) {
            fwrite($zipFile, fread($urlStream, $chunkSize));
        }
        fclose($zipFile);
        fclose($urlStream);

        return $zipFileName;
    }

    /**
     * Download the latest release's zipball.
     *
     * @param string $url
     * @return string
     * @throws RequestException
     */
    protected function downloadZipBall(string $url): string
    {
        $realUrl = $this->getRedirectLocation($url);
        return $this->downloadChunks($realUrl);
    }

    /**
     * Get the Location header from the zipball url.
     *
     * @param string $url
     * @return string|null
     * @throws \Illuminate\Http\Client\RequestException
     */
    protected function getRedirectLocation(string $url): string|null
    {
        $response = Http::withoutRedirecting()
            ->get($url)
            ->throw();
        if($response->redirect()) {
            return $response->header('Location');
        } else {
            ob_start();
            var_dump('$response->header(\'Location\'): "'.$response->header('Location').'"');
            var_dump('$response->header(\'Location:\'): "'.$response->header('Location:').'"');
            var_dump('$response->headers():');
            var_dump($response->headers());
            Log::debug(ob_get_contents());
            ob_end_clean();
        }

        return null;
    }

    /**
     * Get the URL from which the latest zipball can be downloaded.
     *
     * @return string
     * @throws \Illuminate\Http\Client\RequestException
     */
    protected function getZipBallUrl(): string
    {
        $response = Http::get('https://api.github.com/repos/antonioribeiro/countries/releases/latest')
            ->throw();
        $json = json_decode($response, true);

        Log::debug('Zipball URL: "'.$json['zipball_url'].'"');
        return $json['zipball_url'];
    }
}