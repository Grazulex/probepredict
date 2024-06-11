<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProbeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $metrics = $this->metrics->groupBy('metric_type_id')->map(function ($metric) {
            return $metric->sortByDesc('created_at')->first();
        });

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'probe_type' => new ProbeTypeResource($this->probeType),
            'created_at' => new DateTimeResource($this->created_at),
            'updated_at' => new DateTimeResource($this->updated_at),
            'rules' => ProbeRuleResource::collection($this->rules),
            'last_metrics' => ProbeMetricResource::collection($metrics),
        ];
    }
}
