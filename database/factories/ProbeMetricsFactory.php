<?php

namespace Database\Factories;

use App\Models\MetricTypes;
use App\Models\ProbeMetrics;
use App\Models\Probes;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProbeMetricsFactory extends Factory
{
    protected $model = ProbeMetrics::class;

    public function definition(): array
    {
        return [
            'probe_id' => Probes::factory()->create()->id,
            'metric_id' => MetricTypes::factory()->create()->id,
            'value' => $this->faker->randomFloat(2, 0, 100),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
