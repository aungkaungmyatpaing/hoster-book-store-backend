<?php

namespace App\Exceptions;

use App\Traits\ApiResponse;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class Handler extends ExceptionHandler
{
    use ApiResponse;
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (Exception $e, Request $request) {
            return $this->customExceptionHandeller($e, $request);
        });
    }

    public function customExceptionHandeller(Exception $e, Request $request)
    {
        if ($e instanceof RegistrationFailException) {
            return $this->error($e->getMessage(), $e->getCode());
        }

        if ($e instanceof ResourceForbiddenException) {
            return $this->error($e->getMessage(), Response::HTTP_FORBIDDEN);
        }

        if ($e instanceof CreateDataFailException) {
            return $this->error($e->getMessage(), $e->getCode());
        }

        if ($e instanceof DeleteDataFailException) {
            return $this->error($e->getMessage(), $e->getCode());
        }

        if ($e instanceof UpdateDataFailException) {
            return $this->error($e->getMessage(), $e->getCode());
        }

        if ($e instanceof UnprocessableRequestException) {
            return $this->error($e->getMessage(), $e->getCode());
        }


        /**
         * ###########################################
         */

        if ($e instanceof ValidationException) {
            return $this->error($e->getMessage(), Response::HTTP_BAD_REQUEST, $this->getErrors($e->errors()));
        }

        if ($e instanceof ResourceNotFoundException) {
            return $this->error($e->getMessage(), Response::HTTP_NOT_FOUND);
        }

        if ($e instanceof NotFoundHttpException) {
            return $this->error('Route not found', Response::HTTP_NOT_FOUND);
        }

        if ($e instanceof AuthenticationException) {
            return $this->error($e->getMessage(), Response::HTTP_UNAUTHORIZED);
        }
    }


    private function getErrors(array $arr): array
    {
        return array_map(function ($message, $key) {
            return [
                'label'   => $key,
                'detail' => $message[0],
            ];
        }, $arr, array_keys($arr));
    }
}
