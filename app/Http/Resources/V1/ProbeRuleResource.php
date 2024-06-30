<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Models\ProbeRule;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin ProbeRule */
final class ProbeRuleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'rule',
            'id' => $this->id,
            'attributes' => [
                'operator' => $this->operator,
                'condition' => $this->condition,
                'estimation' => $this->estimation,
            ],
            'relationships' => [
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
