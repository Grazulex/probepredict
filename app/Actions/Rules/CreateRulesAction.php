<?php

declare(strict_types=1);

namespace App\Actions\Rules;

use App\Models\ProbeRules;

class CreateRulesAction
{
    public function handle(array $input): ProbeRules
    {
        return ProbeRules::create($input);
    }
}
