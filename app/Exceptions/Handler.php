<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        // ✅ Not Found Routes
        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'success' => false,
                'message' => 'Route not found',
            ], 404);
        }

        // ✅ Model Not Found
        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
        }

        // ✅ Unauthorized Access
        if ($exception instanceof AuthorizationException) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to perform this action',
            ], 403);
        }

        // ✅ Unauthenticated
        if ($exception instanceof AuthenticationException) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required',
            ], 401);
        }

        // ✅ Validation Errors
        if ($exception instanceof ValidationException) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $exception->errors(),
            ], 422);
        }

        // ✅ SQL/DB Errors
        if ($exception instanceof QueryException) {
            return response()->json([
                'success' => false,
                'message' => 'Database error: ' . $exception->getMessage(),
            ], 500);
        }

        // ✅ Laravel's own HTTP Exception
        if ($exception instanceof HttpResponseException) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], $exception->getCode() ?: 500);
        }

        // ✅ General fallback error
        return response()->json([
            'success' => false,
            'message' => 'Something went wrong',
            'error' => config('app.debug') ? $exception->getMessage() : null,
        ], 500);
    }

}
