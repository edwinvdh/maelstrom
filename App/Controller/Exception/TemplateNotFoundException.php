<?php

namespace App\Controller\Exception;

use Exception;
use Throwable;

class TemplateNotFoundException extends Exception
{
    const TEMPLATE_NOT_FOUND = 'Template is not found';
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct(self::TEMPLATE_NOT_FOUND, $code, $previous);
    }
}
