<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Actions\Metrics\CreateMetricAction;
use App\Actions\Metrics\DeleteMetricAction;
use App\Http\Requests\V1\StoreProbeMetricRequest;
use App\Http\Resources\V1\ProbeMetricResource;
use App\Models\ProbeMetric;
use App\Traits\JsonResponses;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class ProbeMetricController
{
    use JsonResponses;
    public function store(StoreProbeMetricRequest $request, CreateMetricAction $createMetricsAction): JsonResponse
    {
        $probeMetric = $createMetricsAction->handle(
            input: $request->only(['probe_id','metric_type_id','value']),
        );

        return $this->successResponse(new ProbeMetricResource($probeMetric), Response::HTTP_CREATED);
    }

    public function destroy(ProbeMetric $probeMetric, DeleteMetricAction $deleteMetricsAction): JsonResponse
    {
        $deleteMetricsAction->handle(
            probeMetric: $probeMetric,
        );

        return $this->successResponse([], Response::HTTP_NO_CONTENT);
    }
}
