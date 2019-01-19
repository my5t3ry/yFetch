<?php
/**
 * User: sascha.bast
 * Date: 12/2/18
 * Time: 1:06 AM
 */

class MetaDataService
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $log;
    private $youtube;
    /**
     * @var CfgService
     */
    public $cfgService;
    /**
     * @var FetchItemService
     */
    private $fetchItemService;
    /**
     * @var ChannelService
     */
    private $channelService;
    private $i;

    public function __construct(\Psr\Log\LoggerInterface $log, CfgService $cfgService, FetchItemService $fetchItemService, ChannelService $channelService)
    {
        $this->cfgService = $cfgService;
        $this->log = $log;
        $this->i = 0;
        $this->youtube = new Madcoda\Youtube\Youtube(array('key' => $this->cfgService->getValue("api-key")));
        $this->fetchItemService = $fetchItemService;
        $this->channelService = $channelService;

    }

    public function extendItemsWithMetaData(array $items)
    {
        foreach ($items as $item) {
            $this->i++;
            try {
                if ($item->getType() == FetchItemType::VIDEO_TYPE() || $item->getType() == FetchItemType::THUMBNAIL_TYPE()) {
                    $item->setMetaData($this->replaceStrings($this->youtube->getVideoInfo($item->getId())));
                } elseif ($item->getType() == FetchItemType::PLAYLIST_TYPE()) {
                    $item->setMetaData($this->replaceStrings($this->youtube->getPlaylistById($item->getId())));
                } elseif ($item->getType() == FetchItemType::CHANNEL_TYPE()) {
                    $item->setMetaData($this->replaceStrings($this->youtube->getChannelById($item->getId())));
                }
                $this->log->info("--> ['" . $this->i . "'] fetched meta data for item with type ['" . $item->getType() . "'] id ['" . $item->getId() . "']");
            } catch (Exception $e) {
                $msg = "-->  failed to fetch meta data for item with id ['" . $item->getId() . "']";
                $this->log->info($msg);
                $this->log->debug($msg . $e->getMessage());
                $this->fetchItemService->removeItemFromQueue($item);
            }
        }
    }

    public function replaceStrings($metaData)
    {
        $cfg = $this->cfgService->getCfg();
        $result = $metaData;
        foreach ($cfg{"meta-data-replace"} as $key => $val) {
            array_walk_recursive($metaData, function($v,$k) use (&$metaData, &$key, &$val) {
                if (is_string($metaData->$k)) {
                    $metaData->$k = preg_replace($key, $val, $metaData->$k);
                }
            });
        }
        return $result;
    }

    public function getCfgService(): CfgService
    {
        return $this->cfgService;
    }

    public function updateChannelService()
    {
        $this->extendItemsWithMetaData($this->channelService->getPlaylistItems());
        $this->extendItemsWithMetaData($this->channelService->getVideoFetchItems());
        $this->extendItemsWithMetaData($this->channelService->getChannels());
        $this->fetchItemService->filterItemsFromConfig([$this->channelService->getVideoFetchItems(), $this->channelService->getPlaylistItems(), $this->channelService->getChannels()]);
    }

    public function updateFetchService()
    {
        $this->extendItemsWithMetaData($this->fetchItemService->getThumbnailItems());
        $this->extendItemsWithMetaData($this->fetchItemService->getVideoItems());
        $this->fetchItemService->filterItemsFromConfig([$this->fetchItemService->getThumbnailItems(), $this->fetchItemService->getVideoItems()]);
    }
}