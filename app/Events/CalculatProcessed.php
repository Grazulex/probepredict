<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\ProbeMetrics;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CalculatProcessed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(ProbeMetrics $probeMetric)
    {
        //get rule for this metric
        $rule = $probeMetric->probe->rules->where('metric_type_id', $probeMetric->metric_type_id)->first();
        if ($rule === null) {
            $condition = 0;
            $operator = '=';
        } else {
            $condition = $rule->condition;
            $operator = $rule->operator;
        }
        $metrics = $probeMetric->probe->metrics->where('metric_type_id', $probeMetric->metric_type_id)->sortBy('created_at');
        $diff_per_sec = 0;
        $quantity = 0;
        foreach ($metrics as $metric) {
            $quantity = $metrics->count();
            $firstMetric = $metric;
            $nextMetric = $probeMetric::where('id', '>', $metric->id)
                ->where('metric_type_id', $probeMetric->metric_type_id)
                ->where('probe_id', $probeMetric->probe_id)
                ->orderBy('id', 'asc')
                ->first();
            if ($nextMetric !== null) {
                $time_difference = ($firstMetric->created_at->diffInSeconds($nextMetric->created_at));
                $value_difference = abs($nextMetric->value - $firstMetric->value);
                $diff_per_sec = $diff_per_sec + ($value_difference / $time_difference);
            }
        }
        if ($quantity > 1) {
            //echo "The average rate of change is: " . $diff_per_sec / $quantity;

            switch ($operator) {
                case '>':
                    $time_to_condition = ($condition - $probeMetric->value) / ($diff_per_sec / $quantity);
                    //echo "The metric will higher than $condition at: " . $probeMetric->created_at->addSeconds($time_to_condition);
                    break;
                case '<':
                    $time_to_condition = ($probeMetric->value - $condition) / ($diff_per_sec / $quantity);
                    //echo "The metric will lower than $condition at: " . $probeMetric->created_at->addSeconds($time_to_condition);
                    break;
                case '=':
                    $time_to_condition = abs($condition - $probeMetric->value) / ($diff_per_sec / $quantity);
                    //echo "The metric will reach $condition at: " . $probeMetric->created_at->addSeconds($time_to_condition);
                    break;
            }
            if ($rule !== null) {
                $rule->estimation = $probeMetric->created_at->addSeconds($time_to_condition);
                $rule->save();
            }
        }
    }
}
