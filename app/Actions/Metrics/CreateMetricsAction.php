<?php

declare(strict_types=1);

namespace App\Actions\Metrics;

use App\Actions\Probes\UpdateOnGoingProbesAction;
use App\Jobs\CalculateJob;
use App\Models\ProbeMetrics;

class CreateMetricsAction
{
    public function __construct(protected UpdateOnGoingProbesAction $updateOnGoingProbesAction) {}
    public function handle(array $input): ProbeMetrics
    {
        $probeMetric = ProbeMetrics::create($input);

        $this->updateOnGoingProbesAction->handle(
            isAdding: true,
            probes: $probeMetric->probe,
        );

        CalculateJob::dispatch(
            probe : $probeMetric->probe,
            probe_type : $probeMetric->probe->probeType,
            metric_type: $probeMetric->metric_type,
        );

        return $probeMetric;

    }
}
