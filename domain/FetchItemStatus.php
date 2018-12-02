<?php
/**
 * User: sascha.bast
 * Date: 12/2/18
 * Time: 12:01 AM
 */

use MyCLabs\Enum\Enum;

class FetchItemStatus extends Enum
{
    const OUTSTANDING = "OUTSTANDING";
    const SUCCESS = "SUCCESS";
    const FAILED = "FAILED";
}