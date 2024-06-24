<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\MetricType;
use Illuminate\Database\Eloquent\Factories\Factory;

final class MetricTypeFactory extends Factory
{
    protected $model = MetricType::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->text,
            'unit' => $this->faker->word,
        ];
    }
}
