<?php

namespace App\Helper;

use App\Infrastructure\PrivateKeyProvider\PrivateKeyProvider;
use phpDocumentor\Reflection\Types\Null_;

/**
 * Methods for encrypting data (for storage).
 * Encryption method used for this case: SHA with a private key and the input data.
 */
class Encrypt
{
    /**
     * @param PrivateKeyProvider $privateKey
     * @param string $valueToEncrypt
     * @return string
     */
    public static function encrypt(PrivateKeyProvider $privateKey, string $valueToEncrypt): string
    {
        return base64_encode($privateKey->getPrivateKey() . ' '. $valueToEncrypt);
    }

    /**
     * @param PrivateKeyProvider $privateKey
     * @param string $valueToDecrypt
     * @return string
     */
    public static function decrypt(PrivateKeyProvider $privateKey, string $valueToDecrypt): string
    {
        $decodedString = base64_decode($valueToDecrypt);
        return self::removePrivateKeyFromString($privateKey, $decodedString);
    }

    private static function removePrivateKeyFromString(PrivateKeyProvider $privateKey, string $subject)
    {
        $length = strlen($privateKey->getPrivateKey());
        return substr($subject, $length + 1, strlen($subject) - $length);
    }
}
