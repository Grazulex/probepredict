<?php

namespace Database\Factories;

use App\Models\MetricTypes;
use Illuminate\Database\Eloquent\Factories\Factory;

class MetricTypesFactory extends Factory
{
    protected $model = MetricTypes::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->text,
            'unit' => $this->faker->word,
            'created_at' => $this->faker->dateTime,
            'updated_at' => $this->faker->dateTime,
        ];
    }
}
