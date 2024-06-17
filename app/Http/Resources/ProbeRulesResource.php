<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\ProbeRules;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin ProbeRules */
final class ProbeRulesResource extends JsonResource
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
            'probe' => $this->when('api/probes' !== $request->route()->getPrefix(), fn() => new ProbesResource($this->probe)),
            'metric_type_id' => new MetricTypesResource($this->metric_type),
            'operator' => $this->operator,
            'condition' => $this->condition,
            'estimation' => new DateTimeResource($this->estimation),
        ];
    }
}
