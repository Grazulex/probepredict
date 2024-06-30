<?php

declare(strict_types=1);

namespace App\Http\Requests\V1;

use App\Traits\JsonResponses;

class StoreProbeMetricRequest extends BaseRequest
{
    use JsonResponses;
    public function rules(): array
    {
        return [
            'probe_id' => ['required', 'exists:probes,id,team_id,' . $this->user()->currentTeam->id],
            'metric_type_id' => ['required', 'exists:metric_types,id'],
            'value' => ['required', 'numeric'],
        ];
    }
}
