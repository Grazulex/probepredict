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
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MetricTypeController
 *
 * @package App\Http\Controllers\API\V1
 *
 * This controller handles all the HTTP requests related to MetricTypes.
 */
final class MetricTypeController
{
    use JsonResponses;

    /**
     * Display a listing of the metric types.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->successResponse(MetricTypeResource::collection(MetricType::all()));
    }

    /**
     * Store a newly created metric type in storage.
     *
     * @param StoreMetricTypeRequest $request
     * @param CreateMetricTypeAction $createMetricTypeAction
     * @return JsonResponse
     */
    public function store(StoreMetricTypeRequest $request, CreateMetricTypeAction $createMetricTypeAction): JsonResponse
    {
        $metric_type = $createMetricTypeAction->handle(
            input:$this->getTypeAttributes($request),
        );

        return $this->successResponse(new MetricTypeResource($metric_type), Response::HTTP_CREATED);
    }

    /**
     * Display the specified metric type.
     *
     * @param MetricType $metricType
     * @return JsonResponse
     */
    public function show(MetricType $metricType): JsonResponse
    {
        return $this->successResponse(new MetricTypeResource($metricType));
    }

    /**
     * Update the specified metric type in storage.
     *
     * @param UpdateMetricTypeRequest $updateMetricTypeRequest
     * @param MetricType $metricType
     * @param UpdateMetricTypeAction $updateMetricTypeAction
     * @return JsonResponse
     */
    public function update(UpdateMetricTypeRequest $updateMetricTypeRequest, MetricType $metricType, UpdateMetricTypeAction $updateMetricTypeAction): JsonResponse
    {
        $metric_type = $updateMetricTypeAction->handle(
            input: $this->getTypeAttributes($updateMetricTypeRequest),
            metricType: $metricType,
        );

        return $this->successResponse(new MetricTypeResource($metric_type));
    }

    /**
     * Remove the specified metric type from storage.
     *
     * @param MetricType $metricType
     * @param DeleteMetricTypeAction $deleteMetricTypeAction
     * @return JsonResponse
     */
    public function destroy(MetricType $metricType, DeleteMetricTypeAction $deleteMetricTypeAction): JsonResponse
    {
        $deleteMetricTypeAction->handle(
            metricType: $metricType,
        );

        return $this->successResponse([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @param  Request  $request
     * @return array
     */
    private function getTypeAttributes(Request $request): array
    {
        return $request->only(['name', 'description', 'unit']);
    }
}
