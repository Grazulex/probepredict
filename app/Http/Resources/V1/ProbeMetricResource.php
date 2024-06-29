<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Models\ProbeMetric;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin ProbeMetric */
final class ProbeMetricResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'metric',
            'id' => $this->id,
            'attributes' => [
                'value' => $this->value,
                'created_at' => new DateTimeResource($this->created_at),
            ],
            'relationships' => [
                'probe' => [
                    'links' => [
                        'self' => route('api.probes.show', ['probe' => $this->probe_id]),
                        'related' => route('api.probes.show', ['probe' => $this->probe_id]),
                    ],
                ],
                'metric_type' => [
                    'links' => [
                        'self' => route('api.metric-types.show', ['metric_type' => $this->metric_type_id]),
                        'related' => route('api.metric-types.show', ['metric_type' => $this->metric_type_id]),
                    ],
                    'data' => new MetricTypeResource($this->metric_type),
                ],
            ],
        ];
    }
}
