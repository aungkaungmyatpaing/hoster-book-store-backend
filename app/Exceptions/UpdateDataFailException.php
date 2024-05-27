<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class UpdateDataFailException extends Exception
{
    public function __construct($message = "update fail", $code = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        parent::__construct($message, $code);
    }
}
