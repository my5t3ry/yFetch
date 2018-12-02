<?php
/**
 * User: sascha.bast
 * Date: 12/2/18
 * Time: 11:29 AM
 */

use Incremental\YouTube\YouTube;

class ChannelService extends AbstractFetchService
{
    private $cfgService;
    private $youtube;
    private $log;

    public function __construct(CfgService $cfgService, \Psr\Log\LoggerInterface $log)
    {
        $this->log = $log;
        $this->cfgService = $cfgService;
        $this->youtube = new YouTube($cfgService->getValue("api-key"));
    }

    private function fetchVideoItems($channel, $pageToken = null)
    {
        $response = $this->youtube->listSearch([
            'part' => 'id',
            'channelId' => $channel,
            'pageToken' => $pageToken,
            "maxResults" => 50,
            'type' => "video"
        ]);
        $result = $response['items'];
        if (array_key_exists('nextPageToken', $response) && count($result) == 50) {
            $result = array_merge($result, $this->fetchVideoItems($channel, $response['nextPageToken']));
        }
        return $result;
    }

    private function fetchPlaylistItems(string $channelId, $pageToken = null)
    {
        $response = $this->youtube->listSearch([
            'part' => 'id',
            'channelId' => $channelId,
            'pageToken' => $pageToken,
            "maxResults" => 50,
            'type' => "playlist"
        ]);
        $result = $response['items'];
        if (array_key_exists('nextPageToken', $response) && count($result) == 50) {
            $result = array_merge($result, $this->fetchPlaylistItems($channelId, $response['nextPageToken']));
        }
        return $result;
    }

    private function createFetchItems($items, $VIDEO_TYPE)
    {
        $result = array();
        foreach ($items as $item) {
            if ($VIDEO_TYPE == FetchItemType::VIDEO_TYPE()) {
                $result[] = new FetchItem($item["id"]["videoId"], $VIDEO_TYPE);
            } elseif ($VIDEO_TYPE == FetchItemType::PLAYLIST_TYPE()) {
                $result[] = new FetchItem($item["id"]["playlistId"], $VIDEO_TYPE);
            } else {
                $result[] = new FetchItem($item["id"]["channelId"], $VIDEO_TYPE);
            }
        }
        return $result;
    }

    public function getPlaylistItems(): array
    {
        return $this->playlistItems;
    }

    public function getVideoFetchItems(): array
    {
        return $this->videoFetchItems;
    }

    public function getChannels(): array
    {
        return $this->channels;
    }

    private function fetchChannelItems(string $channelId, $pageToken = null)
    {
        $response = $this->youtube->listSearch([
            'part' => 'id',
            'channelId' => $channelId,
            'pageToken' => $pageToken,
            "maxResults" => 50,
            'type' => "channel"
        ]);
        $result = $response['items'];
        if (array_key_exists('nextPageToken', $response) && count($result) == 50) {
            $result = array_merge($result, $this->fetchChannelItems($channelId, $response['nextPageToken']));
        }
        return $result;
    }

    public function scrape()
    {
        $channelId = $this->cfgService->getValue("channelid");
        $this->log->info("--> scraping youtube channel ['" . $channelId . "']. depending of the content volume this can take a while...");
        $this->playlistItems = $this->createFetchItems($this->fetchPlaylistItems($channelId), FetchItemType::PLAYLIST_TYPE());
        $this->videoFetchItems = $this->createFetchItems($this->fetchVideoItems($channelId), FetchItemType::VIDEO_TYPE());
        $this->channels = $this->createFetchItems($this->fetchChannelItems($channelId), FetchItemType::CHANNEL_TYPE());;
        $this->log->info("--> ready to scrape ['" . count($this->videoFetchItems) . "'] video Items and ['" . count($this->playlistItems) . "'] playlist Items and ['" . count($this->channels) . "'] channel Items");
    }
}