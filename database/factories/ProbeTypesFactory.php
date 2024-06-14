<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ProbeTypes;
use Illuminate\Database\Eloquent\Factories\Factory;

final class ProbeTypesFactory extends Factory
{
    protected $model = ProbeTypes::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->text,
            'enum' => $this->faker->randomElement(['environment', 'car', 'battery']),
            'created_at' => $this->faker->dateTime,
            'updated_at' => $this->faker->dateTime,
        ];
    }
}
