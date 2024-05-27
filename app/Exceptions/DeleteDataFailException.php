<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class DeleteDataFailException extends Exception
{
    public function __construct($message = "Server error", $code = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        parent::__construct($message, $code);
    }
}
