<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\MetricType;
use App\Models\Probe;
use App\Models\ProbeMetric;
use Illuminate\Database\Eloquent\Factories\Factory;

final class ProbeMetricFactory extends Factory
{
    protected $model = ProbeMetric::class;

    public function definition(): array
    {
        return [
            'probe_id' => Probe::factory()->create()->id,
            'metric_type_id' => MetricType::factory()->create()->id,
            'value' => $this->faker->randomFloat(2, 0, 100),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
