<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Actions\MetricTypes\CreateMetricTypeAction;
use App\Actions\MetricTypes\DeleteMetricTypeAction;
use App\Actions\MetricTypes\UpdateMetricTypeAction;
use App\Http\Requests\V1\StoreMetricTypeRequest;
use App\Http\Requests\V1\UpdateMetricTypeRequest;
use App\Http\Resources\V1\MetricTypeResource;
use App\Models\MetricType;
use App\Traits\JsonResponses;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class MetricTypeController
{
    use JsonResponses;
    public function index(): JsonResponse
    {
        return $this->successResponse(MetricTypeResource::collection(MetricType::all()));
    }

    public function store(StoreMetricTypeRequest $request, CreateMetricTypeAction $createMetricTypeAction): JsonResponse
    {
        $metric_type = $createMetricTypeAction->handle(
            input:$request->only(['name', 'description', 'unit']),
        );

        return $this->successResponse(new MetricTypeResource($metric_type), Response::HTTP_CREATED);
    }

    public function show(MetricType $metricType): JsonResponse
    {
        return $this->successResponse(new MetricTypeResource($metricType));
    }

    public function update(UpdateMetricTypeRequest $updateMetricTypeRequest, MetricType $metricType, UpdateMetricTypeAction $updateMetricTypeAction): JsonResponse
    {
        $metric_type = $updateMetricTypeAction->handle(
            input: $updateMetricTypeRequest->only(['name', 'description', 'unit']),
            metricType: $metricType,
        );

        return $this->successResponse(new MetricTypeResource($metric_type));
    }

    public function destroy(MetricType $metricType, DeleteMetricTypeAction $deleteMetricTypeAction): JsonResponse
    {
        $deleteMetricTypeAction->handle(
            metricType: $metricType,
        );

        return $this->successResponse([], Response::HTTP_NO_CONTENT);
    }
}
