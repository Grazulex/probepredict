<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller as Controller;
use App\Http\Resources\DateTimeResource;
use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    public function sendResponse($result, $message, $status = 200): JsonResponse
    {
        $response = [
            'data' => $result,
            'status' => [
                'message' => $message,
                'code' => $status,
            ],
            'links' => [
                'self' => 'link-value',
            ],
            'requested_by' => [
                'name' => request()->user()->name,
                'roles' => request()->user()->getRoleNames(),
                'team' => request()->user()->currentTeam->name,
                'at' => new DateTimeResource(now()),
            ],
        ];

        return response()->json($response, $status);
    }

    public function sendError($error, $messages = [], $status = 404): JsonResponse
    {
        $response = [
            'data' => [],
            'status' => [
                'message' => $error,
                'code' => $status,
            ],
            'links' => [
                'self' => 'link-value',
            ],
            'requested_by' => [
                'name' => request()->user()->name,
                'roles' => request()->user()->getRoleNames(),
                'team' => request()->user()->currentTeam->name,
                'at' => new DateTimeResource(now()),
            ],
        ];

        if ( ! empty($messages)) {
            $response['data'] = $messages;
        }

        return response()->json($response, $status);
    }
}
