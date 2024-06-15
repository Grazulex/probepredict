<?php

declare(strict_types=1);

namespace App\Actions\Probes;

use App\Models\Probes;
use App\Models\User;

class CreateProbesAction
{
    public function handle(array $input, User $user): Probes
    {
        $input['team_id'] = $user->currentTeam->id;

        return Probes::create($input);
    }
}
