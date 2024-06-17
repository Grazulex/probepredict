<?php

declare(strict_types=1);

namespace App\Actions\Metrics;

use App\Models\ProbeMetrics;

class DeleteMetricsAction
{
    public function handle(ProbeMetrics $probeMetrics): void
    {
        $metric_type = $probeMetrics->metric_type;
        $probe = $probeMetrics->probe;
        $probeMetrics->delete();
        $probe->stats_ongoing = $probe->stats_ongoing + 1;
        $probe->save();

        $probe->probeType->getCalculationStrategy()->calculate($probe, $metric_type);
    }
}
