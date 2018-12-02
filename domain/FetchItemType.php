<?php
/**
 *
 * User: sascha.bast
 * Date: 12/2/18
 * Time: 12:01 AM
 */

use MyCLabs\Enum\Enum;

class FetchItemType extends Enum
{
    const THUMBNAIL_TYPE = "THUMBNAIL_TYPE";
    const VIDEO_TYPE = "VIDEO_TYPE";
    const PLAYLIST_TYPE = "PLAYLIST_TYPE";
    const CHANNEL_TYPE = "CHANNEL_TYPE";
}