<?php

declare(strict_types=1);

namespace App\Actions\Metrics;

use App\Jobs\CalculateJob;
use App\Models\ProbeMetric;

class CreateMetricAction
{
    public function handle(array $input): ProbeMetric
    {
        $value = $input['value'];
        $metric_type_id = $input['metric_type_id'];

        $lastMetric = ProbeMetric::where('metric_type_id', $metric_type_id)
            ->orderBy('created_at', 'desc')
            ->first();
        if ($lastMetric && $lastMetric->value === $value) {
            return $lastMetric;
        }

        $probeMetric = ProbeMetric::create($input);

        CalculateJob::dispatch(
            probe : $probeMetric->probe,
            metric_type: $probeMetric->metric_type,
        );

        return $probeMetric;

    }
}
