<?php

namespace App\Helper\Exception;

use Exception;

class ClassMethodException extends Exception
{
    const CLASS_NOT_FOUND = 'Unable to call method';

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
