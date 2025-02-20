<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponse;
use Throwable;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ApiExceptionMiddleware
{
    public function handle(Request $request, \Closure $next)
    {
        try {
            return $next($request);
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    protected function handleException(Throwable $e)
    {
        if ($e instanceof ValidationException) {
            return ApiResponse::error("Validation Error", $e->errors(), 422);
        }

        if ($e instanceof AuthenticationException) {
            return ApiResponse::error("Unauthenticated", [], 401);
        }

        if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
            return ApiResponse::error("Resource not found", [], 404);
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            return ApiResponse::error("Method not allowed", [], 405);
        }

        return ApiResponse::error("Server error", [], 500);
    }
}
