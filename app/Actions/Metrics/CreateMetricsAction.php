<?php

declare(strict_types=1);

namespace App\Actions\Metrics;

use App\Models\ProbeMetrics;

class CreateMetricsAction
{
    public function handle(array $input): ProbeMetrics
    {
        $probeMetric = ProbeMetrics::create($input);

        $probeMetric->probe->stats_ongoing = $probeMetric->probe->stats_ongoing + 1;
        $probeMetric->probe->save();

        $probeMetric->probe->probeType->getCalculationStrategy()->calculate($probeMetric->probe, $probeMetric->metric_type);

        return $probeMetric;

    }
}
