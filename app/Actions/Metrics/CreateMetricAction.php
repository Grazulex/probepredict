<?php

declare(strict_types=1);

namespace App\Actions\Metrics;

use App\Actions\Probes\UpdateOnGoingProbeAction;
use App\Jobs\CalculateJob;
use App\Models\ProbeMetric;

class CreateMetricAction
{
    public function __construct(protected UpdateOnGoingProbeAction $updateOnGoingProbesAction) {}
    public function handle(array $input): ProbeMetric
    {
        $probeMetric = ProbeMetric::create($input);

        $this->updateOnGoingProbesAction->handle(
            isAdding: true,
            probe: $probeMetric->probe,
        );

        CalculateJob::dispatch(
            probe : $probeMetric->probe,
            probe_type : $probeMetric->probe->probeType,
            metric_type: $probeMetric->metric_type,
        );

        return $probeMetric;

    }
}
