<?php

declare(strict_types=1);

namespace App\Actions\Metrics;

use App\Jobs\CalculateJob;
use App\Models\ProbeMetric;

class DeleteMetricAction
{
    public function handle(ProbeMetric $probeMetric): void
    {
        CalculateJob::dispatch(
            probe :  $probeMetric->probe,
            metric_type: $probeMetric->metric_type,
        );

        $probeMetric->delete();

    }
}
