<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Probe;
use App\Models\ProbeType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

final class ProbeFactory extends Factory
{
    protected $model = Probe::class;

    public function definition(): array
    {
        return [
            'team_id' => User::factory()->withApiToken()->withPersonalTeam()->create()->currentTeam->id,
            'probe_type_id' => ProbeType::factory()->create()->id,
            'name' => $this->faker->name,
            'description' => $this->faker->sentence,
        ];
    }
}
