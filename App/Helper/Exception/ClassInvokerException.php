<?php

namespace App\Helper\Exception;

use Exception;
use Throwable;

class ClassInvokerException extends Exception
{
    const CLASS_NOT_FOUND = 'Unable to load class';

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct(self::CLASS_NOT_FOUND, $code, $previous);
    }
}
