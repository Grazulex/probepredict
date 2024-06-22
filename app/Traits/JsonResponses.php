<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait JsonResponses
{
    public function successResponse($data, $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json($data, $code);
    }

    public function errorResponse($message, $code): JsonResponse
    {
        return response()->json(['error' => $message], $code);
    }
}
