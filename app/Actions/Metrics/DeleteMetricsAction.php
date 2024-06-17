<?php

declare(strict_types=1);

namespace App\Actions\Metrics;

use App\Actions\Probes\UpdateOnGoingProbesAction;
use App\Jobs\CalculateJob;
use App\Models\ProbeMetrics;

class DeleteMetricsAction
{
    public function __construct(protected UpdateOnGoingProbesAction $updateOnGoingProbesAction) {}
    public function handle(ProbeMetrics $probeMetrics): void
    {

        $probe = $probeMetrics->probe;

        $this->updateOnGoingProbesAction->handle(
            isAdding: false,
            probes: $probe,
        );

        CalculateJob::dispatch(
            probe :  $probeMetrics->probe,
            probe_type : $probe->probeType,
            metric_type: $probeMetrics->metric_type,
        );

        $probeMetrics->delete();

    }
}
