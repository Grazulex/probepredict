<?php

declare(strict_types=1);

namespace App\Actions\MetricTypes;

use App\Models\MetricType;

class DeleteMetricTypeAction
{
    public function handle(MetricType $metricType): void
    {
        $metricType->delete();
    }
}
