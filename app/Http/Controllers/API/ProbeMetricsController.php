<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Requests\DeleteProbeMetricRequest;
use App\Http\Requests\StoreProbeMetricRequest;
use App\Http\Resources\ProbeMetricResource;
use App\Models\ProbeMetrics;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class ProbeMetricsController extends BaseController
{
    public function store(StoreProbeMetricRequest $request): JsonResponse
    {
        $input = $request->validated();
        $probeMetric = ProbeMetrics::create($input);
        $probeMetric->probe->probeType->getCalculationStrategy()->calculate($probeMetric->probe, $probeMetric->metric_type);

        return $this->sendResponse(
            result: new ProbeMetricResource($probeMetric),
            message: 'Metric created successfully.',
            status: Response::HTTP_CREATED,
        );
    }

    public function destroy(DeleteProbeMetricRequest $request, ProbeMetrics $probeMetrics): JsonResponse
    {
        $metric_type = $probeMetrics->metric_type;
        $probe = $probeMetrics->probe;
        $probeMetrics->delete();
        $probe->probeType->getCalculationStrategy()->calculate($probe, $metric_type);

        return $this->sendResponse(
            result: [],
            message: 'Metric deleted successfully.',
            status: Response::HTTP_NO_CONTENT,
        );
    }
}
