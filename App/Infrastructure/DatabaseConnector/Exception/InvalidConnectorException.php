<?php

namespace App\Infrastructure\DatabaseConnector\Exception;

use Exception;
use Throwable;

class InvalidConnectorException extends Exception
{
    const INVALID_CONNECTION = 'Something went wrong';  // keeping it vague ;)

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct(self::INVALID_CONNECTION, $code, $previous);
    }
}
