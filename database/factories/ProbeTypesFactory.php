<?php

namespace Database\Factories;

use App\Models\ProbeTypes;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProbeTypesFactory extends Factory
{
    protected $model = ProbeTypes::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->text,
            'created_at' => $this->faker->dateTime,
            'updated_at' => $this->faker->dateTime,
        ];
    }
}
