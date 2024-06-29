<?php

declare(strict_types=1);

namespace App\Strategies;

use App\Models\MetricType;
use App\Models\Probe;
use App\Models\ProbeMetric;

final class EnvironmentalStrategy implements CalculationStrategy
{
    public function calculate(Probe $probes, MetricType $metricTypes): void
    {
        $rule = $probes->rules->where('metric_type_id', $metricTypes->id)->first();
        if (null === $rule) {
            $condition = 0;
            $operator = '=';
        } else {
            $condition = $rule->condition;
            $operator = $rule->operator;
        }
        $metrics = $probes->metrics->where('metric_type_id', $metricTypes->id)->sortBy('created_at');
        $diff_per_sec = 0;
        $quantity = 0;
        foreach ($metrics as $metric) {
            $quantity = $metrics->count();
            $firstMetric = $metric;
            $nextMetric = ProbeMetric::where('id', '>', $metric->id)
                ->where('metric_type_id', $metricTypes->id)
                ->where('probe_id', $probes->id)
                ->orderBy('id', 'asc')
                ->first();
            if (null !== $nextMetric) {
                $time_difference = ($firstMetric->created_at->diffInSeconds($nextMetric->created_at));
                $value_difference = abs((int) $nextMetric->value - (int) $firstMetric->value);
                $diff_per_sec = $diff_per_sec + ($value_difference / $time_difference);
            }
        }
        if ($quantity > 1) {
            $time_to_condition = 0;
            $probeMetric = $metrics->last();
            switch ($operator) {
                case '>':
                    $time_to_condition = ($condition - (int) $probeMetric->value) / ($diff_per_sec / $quantity);
                    break;
                case '<':
                    $time_to_condition = ((int) $probeMetric->value - $condition) / ($diff_per_sec / $quantity);
                    break;
                case '=':
                    $time_to_condition = abs($condition - (int) $probeMetric->value) / ($diff_per_sec / $quantity);
                    break;
            }
            if (null !== $rule) {
                $rule->estimation = $probeMetric->created_at->addSeconds($time_to_condition);
                $rule->save();
            }
        }
        $probes->stats_ongoing = $probes->stats_ongoing - 1;
        $probes->save();
    }
}
