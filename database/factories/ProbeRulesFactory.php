<?php

namespace Database\Factories;

use App\Models\MetricTypes;
use App\Models\ProbeRules;
use App\Models\Probes;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProbeRulesFactory extends Factory
{
    protected $model = ProbeRules::class;

    public function definition(): array
    {
        return [
            'probe_id' => Probes::factory()->create()->id,
            'metric_type_id' => MetricTypes::factory()->create()->id,
            'operator' => $this->faker->randomElement(['>', '<', '=']),
            'condition' => $this->faker->randomFloat(2, 0, 100),
        ];
    }
}
