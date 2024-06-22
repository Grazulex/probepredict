<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\MetricType;
use App\Models\Probe;
use App\Models\ProbeRule;
use Illuminate\Database\Eloquent\Factories\Factory;

final class ProbeRuleFactory extends Factory
{
    protected $model = ProbeRule::class;

    public function definition(): array
    {
        return [
            'probe_id' => Probe::factory()->create()->id,
            'metric_type_id' => MetricType::factory()->create()->id,
            'operator' => $this->faker->randomElement(['>', '<', '=']),
            'condition' => $this->faker->randomFloat(2, 0, 100),
        ];
    }
}
