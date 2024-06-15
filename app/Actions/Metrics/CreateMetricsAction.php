<?php

declare(strict_types=1);

namespace App\Actions\Metrics;

use App\Models\ProbeMetrics;

class CreateMetricsAction
{
    public function handle(array $input): ProbeMetrics
    {
        $probeMetric = ProbeMetrics::create($input);
        $probeMetric->probe->probeType->getCalculationStrategy()->calculate($probeMetric->probe, $probeMetric->metric_type);

        return $probeMetric;

    }
}
