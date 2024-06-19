<?php

declare(strict_types=1);

namespace App\Actions\Probes;

use App\Models\Probes;
use App\Models\ProbeTypes;
use App\Models\User;
use phpDocumentor\Reflection\DocBlock\Description;

class CreateProbesAction
{
    /**
     * @param  array{name: string, description: string, probe_type_id: ProbeTypes}  $input
     * @param  User  $user
     * @return Probes
     */
    public function handle(array $input, User $user): Probes
    {
        $input['team_id'] = $user->currentTeam->id;

        return Probes::create($input);
    }
}
