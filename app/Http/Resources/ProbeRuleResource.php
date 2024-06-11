<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProbeRuleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'probe' => $this->when($request->route()->getPrefix() !== 'api/probes', function () {
                return new ProbeResource($this->probe);
            }),
            'metric_type_id' => new MetricTypeResource($this->metric_type),
            'operator' => $this->operator,
            'condition' => $this->condition,
            'estimation' => new DateTimeResource($this->estimation),
        ];
    }
}
