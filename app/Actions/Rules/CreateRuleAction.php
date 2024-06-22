<?php

declare(strict_types=1);

namespace App\Actions\Rules;

use App\Models\ProbeRule;

class CreateRuleAction
{
    public function handle(array $input): ProbeRule
    {
        return ProbeRule::create($input);
    }
}
