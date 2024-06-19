<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller as Controller;
use App\Http\Resources\V1\DateTimeResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends Controller
{
    public function sendResponse($result, $message, $status = Response::HTTP_OK, $paginator = false): JsonResponse
    {
        if ($paginator) {
            $response = [
                'data' => $result->items(),
                'pagination' => [
                    'current_page' => $result->currentPage(),
                    'last_page' => $result->lastPage(),
                    'per_page' => $result->perPage(),
                    'total' => $result->total(),
                    'next_page_url' => $result->nextPageUrl(),
                    'prev_page_url' => $result->previousPageUrl(),
                ],
            ];
        } else {
            $response = [
                'data' => $result,
            ];
        }

        $response['status'] = [
            'message' => $message,
            'code' => $status,
        ];

        $response['requested_by'] = [
            'name' => request()->user()->name,
            'roles' => request()->user()->getRoleNames(),
            'team' => request()->user()->currentTeam->name,
            'at' => new DateTimeResource(now()),
        ];

        return response()->json($response, $status);
    }

    public function sendError($error, $messages = [], $status = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        $response = [
            'data' => [],
            'status' => [
                'message' => $error,
                'code' => $status,
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
