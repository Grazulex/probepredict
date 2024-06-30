<?php

declare(strict_types=1);

namespace App\Actions\Metrics;

use App\Actions\Probes\UpdateOnGoingProbeAction;
use App\Jobs\CalculateJob;
use App\Models\ProbeMetric;

class DeleteMetricAction
{
    public function __construct(protected UpdateOnGoingProbeAction $updateOnGoingProbesAction) {}
    public function handle(ProbeMetric $probeMetric): void
    {

        $probe = $probeMetric->probe;

        $this->updateOnGoingProbesAction->handle(
            isAdding: false,
            probe: $probe,
        );

        CalculateJob::dispatch(
            probe :  $probeMetric->probe,
            metric_type: $probeMetric->metric_type,
        );

        $probeMetric->delete();

    }
}
