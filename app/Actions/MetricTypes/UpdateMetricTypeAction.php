<?php

declare(strict_types=1);

namespace App\Actions\MetricTypes;

use App\Models\MetricType;

class UpdateMetricTypeAction
{
    public function handle(array $input, MetricType $metricType): MetricType
    {
        $metricType->update($input);

        return $metricType;
    }
}
