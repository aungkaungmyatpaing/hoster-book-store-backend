<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class RegistrationFailException extends Exception
{
    public function __construct($message = "Registration fail", $code = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        parent::__construct($message, $code);
    }
}
