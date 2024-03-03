<?php

namespace App\Infrastructure\Laravel\Exceptions;

use App\Domain\Sales\Exception\SalesException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use JsonException;
use Throwable;

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
        });

        $this->renderable(function (SalesException $exception, $request) {
            return $this->handleSalesException($exception);
        });
    }

     /**
     * Handle SalesException.
     *
     * @param  SalesException  $exception
     * @return JsonResponse
     */
    protected function handleSalesException(SalesException $exception): JsonResponse
    {
        return response()->json([
            'status' => $exception->getCode(),
            'message' => $exception->getMessage(),
        ], $exception->getCode());
    }
}
