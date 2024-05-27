<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class UnprocessableRequestException extends Exception
{
    public function __construct($message = "Request can not process", $code = Response::HTTP_BAD_REQUEST)
    {
        parent::__construct($message, $code);
    }
}
