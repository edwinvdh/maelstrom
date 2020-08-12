<?php

namespace App\Helper;

use DateTime;

class SqlDateTime
{
    public static function getSqlDate(DateTime $dateTime)
    {
        return $dateTime->format('Y-m-d H:i:s');
    }
}
