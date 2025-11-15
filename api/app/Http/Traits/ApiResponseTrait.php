<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    /**
     * Standard success response.
     */
    protected function apiSuccess(mixed $data, int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'ok' => true,
            'data' => $data,
        ], $statusCode);
    }

    /**
     * Standard error response.
     */
    protected function apiError(string $message, int $statusCode = 400): JsonResponse
    {
        // You can add more context, like 'error_code', 'details', etc.
        return response()->json([
            'ok' => false,
            'error' => $message,
        ], $statusCode);
    }
}