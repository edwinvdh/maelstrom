<?php

namespace App\Repository\Mapper\Exception;

use Exception;
use Throwable;

class UserMapperException extends Exception
{
    const ERROR_WHILE_MAPPING_WITH_FIELD = 'Unable to map field';
    public function __construct($field = "", $code = 0, Throwable $previous = null)
    {
        $message = self::ERROR_WHILE_MAPPING_WITH_FIELD . ' ' . $field;
        parent::__construct($message, $code, $previous);
    }
}
