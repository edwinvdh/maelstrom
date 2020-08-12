<?php

namespace App\Helper;

/**
 * UUID class
 *
 * @author Andrew Moore
 * @link http://www.php.net/manual/en/function.uniqid.php#94959
 */
class Uuid
{
    /**
     * Generate v4 UUID
     */
    public static function createUuid()
    {
        /** random generated hashes */
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }

    //** check 8chars-4chars-4chars-4chars-12chars */
    public static function is_valid($uuid)
    {
        return preg_match('/^\{?[0-9a-f]{8}\-?[0-9a-f]{4}\-?[0-9a-f]{4}\-?'.
                          '[0-9a-f]{4}\-?[0-9a-f]{12}\}?$/i', $uuid) === 1;
    }
}
