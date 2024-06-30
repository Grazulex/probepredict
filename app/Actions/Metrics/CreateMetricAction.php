<?php

declare(strict_types=1);

namespace App\Actions\Metrics;

use App\Jobs\CalculateJob;
use App\Models\ProbeMetric;

class CreateMetricAction
{
    public function handle(array $input): ProbeMetric
    {
        $probeMetric = ProbeMetric::create($input);

        CalculateJob::dispatch(
            probe : $probeMetric->probe,
            metric_type: $probeMetric->metric_type,
        );

        return $probeMetric;

    }
}
