<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\MetricType;
use App\Models\Probe;
use App\Models\Stat;
use Illuminate\Database\Eloquent\Factories\Factory;

class StatFactory extends Factory
{
    protected $model = Stat::class;

    public function definition(): array
    {
        return [
            'probe_id' => Probe::factory()->create()->id,
            'metric_type_id' => MetricType::factory()->create()->id,
            'started_at' => $this->faker->dateTime(),
            'ended_at' => $this->faker->dateTime(),
            'avg_increase_minute' => $this->faker->randomFloat(2, 0, 100),
            'avg_decrease_minute' => $this->faker->randomFloat(2, 0, 100),
            'min' => $this->faker->randomFloat(2, 0, 100),
            'max' => $this->faker->randomFloat(2, 0, 100),
        ];
    }
}
