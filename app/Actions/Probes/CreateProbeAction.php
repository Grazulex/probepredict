<?php

declare(strict_types=1);

namespace App\Actions\Probes;

use App\Models\Probe;
use App\Models\ProbeType;
use App\Models\User;
use phpDocumentor\Reflection\DocBlock\Description;

class CreateProbeAction
{
    /**
     * @param  array{name: string, description: string, probe_type_id: ProbeType}  $input
     * @param  User  $user
     * @return Probe
     */
    public function handle(array $input, User $user): Probe
    {
        $input['team_id'] = $user->currentTeam->id;

        return Probe::create($input);
    }
}
