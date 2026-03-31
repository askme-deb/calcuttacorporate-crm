<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $exception)
    {
        // Return JSON response for authentication errors
        if ($exception instanceof AuthenticationException) {
            return response()->json([
                'error' => 'Unauthenticated. Token is invalid or missing.'
            ], 401);
        }

        return parent::render($request, $exception);
    }
}
