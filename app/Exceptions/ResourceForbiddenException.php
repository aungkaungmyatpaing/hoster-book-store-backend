<?php

namespace App\Exceptions;

use Exception;

class ResourceForbiddenException extends Exception
{
    public function __construct($message = "Invalid credentials", $code = 403)
    {
        parent::__construct($message, $code);
    }
}
