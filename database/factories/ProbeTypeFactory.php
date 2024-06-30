<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ProbeType;
use Illuminate\Database\Eloquent\Factories\Factory;

final class ProbeTypeFactory extends Factory
{
    protected $model = ProbeType::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->text,
        ];
    }
}
