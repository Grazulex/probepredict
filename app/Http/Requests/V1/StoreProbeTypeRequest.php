<?php

declare(strict_types=1);

namespace App\Http\Requests\V1;

use App\Traits\JsonResponses;

class StoreProbeTypeRequest extends BaseRequest
{
    use JsonResponses;
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:probe_types'],
            'description' => 'required',
        ];
    }
}
