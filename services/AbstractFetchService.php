<?php
/**
 * User: sascha.bast
 * Date: 12/2/18
 * Time: 11:58 AM
 */

abstract class AbstractFetchService
{
    public function removeItemFromQueue($item, $queue)
    {
        $queue = array_filter($queue, function ($v) use ($item) {
            return $v->getId() == $item->getId() ? $v : false;
        });
    }

    public function filterItemsFromConfig(array $fetchItemTypeLists)
    {
        $cfg = $this->getCfgService()->getCfg();
        if (is_array($cfg{"access"})) {
            foreach ($fetchItemTypeLists as $fetchItemTypeList) {
                foreach ($fetchItemTypeList as $item) {
                    if ($item->getMetaData() != null && array_key_exists("status", $item->getMetaData())) {
                        if ($item->getMetaData() != null && !in_array($item->getMetaData()->status->privacyStatus, $cfg{"access"})) {
                            $this->removeItemFromQueue($item, $fetchItemTypeList);
                        }
                    }
                }
            }
        }
        if (is_array($cfg{"tags"})) {
            foreach ($fetchItemTypeLists as $fetchItemTypeList) {
                if ($item->getMetaData() != null) {
                    foreach ($fetchItemTypeList as $item) {
                        if ($item->getMetaData() != null && array_key_exists("tags", $item->getMetaData()->snippet)) {
                            if (!in_array($item->getMetaData()->snippet->tags, $cfg{"tags"})) {
                                $this->removeItemFromQueue($item, $fetchItemTypeList);
                            }
                        }
                    }
                }
            }
        }
        if (!empty($cfg{"tagCountThreshold"})) {
            foreach ($fetchItemTypeLists as $fetchItemTypeList) {
                foreach ($fetchItemTypeList as $item) {
                    if ($item->getMetaData() != null && array_key_exists("tags", $item->getMetaData()->snippet)) {
                        if (count($item->getMetaData()->snippet->tags) < intval($cfg{"tagCountThreshold"})) {
                            $this->removeItemFromQueue($item, $fetchItemTypeList);
                        }
                    }
                }
            }
        }
        if (!empty($cfg{"startdate"})) {
            foreach ($fetchItemTypeLists as $fetchItemTypeList) {
                foreach ($fetchItemTypeList as $item) {
                    if ($item->getMetaData() != null && array_key_exists("snippet", $item->getMetaData())) {
                        if (date_create($item->getMetaData()->snippet->publishedAt) < $cfg{"startdate"}) {
                            $this->removeItemFromQueue($item, $fetchItemTypeList);
                        }
                    }
                }
            }
        }
        if (!empty($cfg{"enddate"})) {
            foreach ($fetchItemTypeLists as $fetchItemTypeList) {
                foreach ($fetchItemTypeList as $item) {
                    if ($item->getMetaData() != null && array_key_exists("snippet", $item->getMetaData())) {
                        if (date_create($item->getMetaData()->snippet->publishedAt) > $cfg{"enddate"}) {
                            $this->removeItemFromQueue($item, $fetchItemTypeList);
                        }
                    }
                }
            }
        }
    }
}