<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Models\Probe;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Probe */
final class ProbeResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        $metrics = $this->metrics->groupBy('metric_type_id')->map(fn($metric) => $metric->sortByDesc('created_at')->first());

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'stats_ongoing' => $this->stats_ongoing,
            'probe_type' => new ProbeTypeResource($this->probeType),
            'created_at' => new DateTimeResource($this->created_at),
            'updated_at' => new DateTimeResource($this->updated_at),
            'rules' => $this->when('api/probes' === $request->route()->getPrefix(), fn() => ProbeRuleResource::collection($this->rules)),
            'last_metrics' => ProbeMetricResource::collection($metrics),
        ];
    }
}
