<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Http\Resources\V1\MetricTypeResource;
use App\Models\MetricType;
use App\Traits\JsonResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

final class MetricTypeController
{
    use JsonResponses;
    public function index(): JsonResponse
    {
        return $this->successResponse(MetricTypeResource::collection(MetricType::all()));
    }

    public function store(Request $request): JsonResponse
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => ['required', 'string', 'max:255', 'unique:metric_types'],
            'description' => 'required',
            'unit' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $metric_types = MetricType::create($input);

        return $this->successResponse(new MetricTypeResource($metric_types), Response::HTTP_CREATED);
    }

    public function show(int $id): JsonResponse
    {
        $metric_types = MetricType::find($id);

        if (null === $metric_types) {
            return $this->errorResponse('Metric not found.', Response::HTTP_NOT_FOUND);
        }

        return $this->successResponse(new MetricTypeResource($metric_types));
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $metric_types = MetricType::find($id);

        if (null === $metric_types) {
            return $this->errorResponse('Metric not found.', Response::HTTP_NOT_FOUND);
        }

        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => ['required', 'string', 'max:255', 'unique:mertic_types'],
            'description' => 'required',
            'unit' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $metric_types->name = $input['name'];
        $metric_types->description = $input['description'];
        $metric_types->unit = $input['unit'];
        $metric_types->save();

        return $this->successResponse(new MetricTypeResource($metric_types));
    }

    public function destroy(int $id): JsonResponse
    {
        $metric_types = MetricType::find($id);

        if (null === $metric_types) {
            return $this->errorResponse('Metric not found.', Response::HTTP_NOT_FOUND);
        }

        $metric_types->delete();

        return $this->successResponse([], Response::HTTP_NO_CONTENT);
    }
}
