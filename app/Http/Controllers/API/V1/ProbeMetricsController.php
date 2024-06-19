<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Actions\Metrics\CreateMetricsAction;
use App\Actions\Metrics\DeleteMetricsAction;
use App\Http\Requests\V1\StoreProbeMetricRequest;
use App\Http\Resources\V1\ProbeMetricsResource;
use App\Models\ProbeMetrics;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class ProbeMetricsController extends BaseController
{
    public function store(StoreProbeMetricRequest $request, CreateMetricsAction $createMetricsAction): JsonResponse
    {
        $probeMetric = $createMetricsAction->handle(
            input: $request->only(['probe_id','metric_type_id','value']),
        );

        return $this->sendResponse(
            result: new ProbeMetricsResource($probeMetric),
            message: 'Metric created successfully.',
            status: Response::HTTP_CREATED,
        );
    }

    public function destroy(ProbeMetrics $probeMetrics, DeleteMetricsAction $deleteMetricsAction): JsonResponse
    {
        $deleteMetricsAction->handle(
            probeMetrics: $probeMetrics,
        );

        return $this->sendResponse(
            result: [],
            message: 'Metric deleted successfully.',
            status: Response::HTTP_NO_CONTENT,
        );
    }
}
