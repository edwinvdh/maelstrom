<?php

namespace App\Helper;

use phpDocumentor\Reflection\Types\Mixed_;

class UrlValidator
{
    /**
     * Array|bool. Multiple return types will be introduced in PHP 8 ;)
     * @param string $url
     * @return mixed
     */
    public static function isValid(string $url = '')
    {
        // Todo: see if we can fix UTF-8 encoded URL. (never done this before)

        if ($url == '') {
            // default method will be getting the url from the _SERVER global var
            $url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        }
        return filter_var($url, FILTER_VALIDATE_URL);
    }
}
