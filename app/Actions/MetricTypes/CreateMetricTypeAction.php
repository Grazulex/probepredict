<?php

declare(strict_types=1);

namespace App\Actions\MetricTypes;

use App\Models\MetricType;

class CreateMetricTypeAction
{
    public function handle(array $input): MetricType
    {
        return MetricType::create($input);
    }
}
