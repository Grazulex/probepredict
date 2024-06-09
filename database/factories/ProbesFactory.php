<?php

namespace Database\Factories;

use App\Models\Probes;
use App\Models\ProbeTypes;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProbesFactory extends Factory
{
    protected $model = Probes::class;

    public function definition(): array
    {
        return [
            'team_id' => User::factory()->withApiToken()->withPersonalTeam()->create()->currentTeam->id,
            'probe_type_id' => ProbeTypes::factory()->create()->id,
            'name' => $this->faker->name,
            'description' => $this->faker->sentence,
        ];
    }
}
