<?php

/**
 * User: sascha.bast
 * Date: 12/2/18
 * Time: 12:51 AM
 */

use Masih\YoutubeDownloader\YoutubeDownloader;

class  FetchService
{

    private $cfgService;
    private $log;
    private $client;
    private $fetchItemService;

    public function __construct(CfgService $cfgService, \Psr\Log\LoggerInterface $log, FetchItemService $fetchItemService)
    {
        $this->cfgService = $cfgService;
        $this->log = $log;
        $this->client = new \GuzzleHttp\Client();
        $this->fetchItemService = $fetchItemService;
        $this->fetchFetchItems();

    }

    public function fetchFetchItems()
    {
        $this->fetchThumbnails($this->fetchItemService->getThumbnailItems());
        $this->fetchVideos($this->fetchItemService->getVideoItems(), $this->cfgService->getValue("force-overrider"));
    }

    private function fetchThumbnails(array $items, $overwrite = true)
    {
        $this->log->info("---> start to fetch thumbnails");
        foreach ($items as $item) {
            $dataUrl = $item->getDataUrl($this->cfgService);
            $loc = $this->getOutputDir($item) . DIRECTORY_SEPARATOR . $item->getMetaData()->{"id"} . "_" . str_replace("\"", "", $item->getMetaData()->snippet->title) . ".jpg";
            $this->log->info("Downloading ['" . $item->getDataUrl($this->cfgService) . "'] ->");
            $this->log->info($loc);
            $result = $this->download($dataUrl, $loc);
            if ($result["response_code"] == 200) {
                $this->log->info("done.");
            }
        }
    }

    public function download($url, $loc)
    {
        $file_path = fopen($loc, 'w+');
        $client = new \GuzzleHttp\Client();
        $response = $client->get($url, ['save_to' => $file_path]);
        return ['response_code' => $response->getStatusCode(), 'name' => $loc];
    }

    private function fetchVideos(array $getVideoItems)
    {
        $totalItems = count($getVideoItems);
        $currentIndex = 0;
        $this->log->info("---> start to fetch videos");
        foreach ($getVideoItems as $videoItems) {
            $currentIndex++;
            $youtube = new YoutubeDownloader($videoItems->getId());
            $youtube->setPath($this->getOutputDir($videoItems));;
            $youtube->onProgress = function ($downloadedBytes, $fileSize, $index, $count) use ($totalItems, $currentIndex, $videoItems) {
                if ($count > 1) echo '[' . $index . ' of ' . $count . ' videos] ';
                if ($fileSize > 0)
                    echo "\r" . '[' . $videoItems->getId . '] [' . $currentIndex . '/' . $totalItems . '] Downloaded ' . $downloadedBytes . ' of ' . $fileSize . ' bytes [%' . number_format($downloadedBytes * 100 / $fileSize, 2) . '].';
                else
                    echo "\r" . 'Downloading...';
            };
            $youtube->onComplete = function ($filePath, $fileSize, $index, $count) use ($currentIndex) {
                if ($count > 1) echo '[' . $index . ' of ' . $count . ' videos] ';
                echo PHP_EOL . 'Downloading of ' . $fileSize . ' bytes has been completed. It is saved in ' . $filePath . PHP_EOL;
            };
            $youtube->download();
        }
    }

    public function getOutputDir($item = ""): string
    {
        if ($this->cfgService->getValue("directorySaveStrategy") == "flat" || $item == "") {
            return __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "output";
        } else {
            $dir = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "output" . DIRECTORY_SEPARATOR . $item->getId();
            if (!file_exists($dir)) {
                $this->log->info("created dir: ['" . $dir . "']");
                mkdir($dir);
            }
            return $dir;
        }
    }


    private function delete_directory($dirname)
    {
        if (is_dir($dirname))
            $dir_handle = opendir($dirname);
        if (!$dir_handle)
            return false;
        while ($file = readdir($dir_handle)) {
            if ($file != " . " && $file != " ..") {
                if (!is_dir($dirname . " / " . $file))
                    unlink($dirname . " / " . $file);
                else
                    $this->delete_directory($dirname . '/' . $file);
            }
        }
        closedir($dir_handle);
        rmdir($dirname);
        return true;
    }
}