<?php
/**
 * User: sascha.bast
 * Date: 12/1/18
 * Time: 11:24 PM
 */

class FetchItemService extends AbstractFetchService
{
    private $thumbnailItems = array();
    private $videoItems = array();
    private $log;
    private $thumbFile = __DIR__ . "/../input/thumbnails.txt";
    private $videoFile = __DIR__ . "/../input/videos.txt";
    private $cfgService;

    public function __construct(Psr\Log\LoggerInterface $log, CfgService $cfgService)
    {
        $this->log = $log;
        $this->cfgService = $cfgService;
    }

    private function getStripedFileContents(string $file, FetchItemType $type): array
    {
        $result = array();
        $arr = file($file);
        $items = array();
        foreach ($arr as $value) {
            if ('' === trim($value) || strpos($value, '#') === 0) {
                continue;
            }
            $items[] = str_replace(array("\r", "\n"), '', $value);
        }
        foreach ($items as $item) {
            preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $item, $matches);
            foreach ($matches as $match) {
                if (!empty($match)) {
                    $result[] = new FetchItem($match, $type);
                    $this->log->debug("Found id['" . $match . "']");
                }
            }
        }
        return $result;
    }

    public function getCfgService(): CfgService
    {
        return $this->cfgService;
    }

    public function getThumbnailItems(): array
    {
        return $this->thumbnailItems;
    }

    public function getVideoItems(): array
    {
        return $this->videoItems;
    }

    public function parseInput()
    {
        $this->thumbnailItems = $this->getStripedFileContents($this->thumbFile, FetchItemType::THUMBNAIL_TYPE());
        $this->videoItems = $this->getStripedFileContents($this->videoFile, FetchItemType::VIDEO_TYPE());
        $this->log->info("--> parsing download items ['" . count($this->thumbnailItems) . "'] Thumbnail Items and ['" . count($this->videoItems) . "'] Video Items");
    }
}