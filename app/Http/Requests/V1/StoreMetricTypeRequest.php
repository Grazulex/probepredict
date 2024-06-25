<?php

declare(strict_types=1);

namespace App\Http\Requests\V1;

use App\Traits\JsonResponses;

class StoreMetricTypeRequest extends BaseRequest
{
    use JsonResponses;
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:metric_types'],
            'description' => 'required',
            'unit' => 'required',
        ];
    }
}
