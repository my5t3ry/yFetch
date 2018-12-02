<?php
/**
 * User: sascha.bast
 * Date: 12/2/18
 * Time: 12:10 PM
 */

class MetrixService
{
    private $log;
    private $cfgService;
    private $lineBreak = PHP_EOL;
    private $channelService;

    public function __construct(\Psr\Log\LoggerInterface $log, CfgService $cfgService, ChannelService $channelService)
    {
        $this->log = $log;
        $this->cfgService = $cfgService;
        $this->channelService = $channelService;
    }

    public function buildMetrix($cfgObject, $outputPath, $delimiter, $items)
    {
        $rows = array();
        $rows[] = $this->buildHeader($cfgObject, $delimiter);
        $finalRows = array_merge($rows, $this->buildBody($cfgObject, $items, $delimiter));
        $fp = fopen($outputPath, "w");
        foreach ($finalRows as $row) {
            $data = str_getcsv($row);
            fputcsv($fp, $data);
        }
        fclose($fp);
    }

    private function buildHeader($cfgObject, $delimiter)
    {
        $result = "";
        foreach ($cfgObject as $key => $val) {
            $result = $result . $key . $delimiter;
        }
        return $result . $this->lineBreak;
    }

    private function buildBody($cfgObject, $items, $delimiter)
    {
        $result = array();
        $str = "";
        foreach ($items as $item) {
            $str = "";
            foreach ($cfgObject as $key => $val) {
                $str = $str . $this->getPathValue($item, $val) . $delimiter;
            }
            $result[] = $str;
        }
        return $result;
    }

    private function getPathValue($item, $val)
    {
        $value = array_reduce(explode('.', $val), function ($o, $p) {
            return is_numeric($p) ? ($o[$p] ?? null) : ($o->$p ?? null);
        }, $item->getMetaData());
        if (is_array($value)) {
            return implode("|", $value);
        }
        return $value;
    }

    /**
     * @param CfgService $cfgService
     */
    public function build()
    {
        $this->log->info("--> generating meta data csvs ...");
        $cfg = $this->cfgService->getCfg();
        $this->buildMetrix($cfg{"video-meta-mapping"}, __DIR__ . $cfg{"video-csv-name"}, $cfg{"video-csv-delimiter"}, $this->channelService->getVideoFetchItems());
        $this->buildMetrix($cfg{"channel-meta-mapping"}, __DIR__ . $cfg{"channel-csv-name"}, $cfg{"channel-csv-delimiter"}, $this->channelService->getChannels());
        $this->buildMetrix($cfg{"playlist-meta-mapping"}, __DIR__ . $cfg{"playlist-csv-name"}, $cfg{"playlist-csv-delimiter"}, $this->channelService->getPlaylistItems());
    }
}