<?php

declare(strict_types=1);

namespace App\Http\Requests\V1;

use App\Traits\JsonResponses;
use Illuminate\Contracts\Validation\ValidationRule;

class UpdateProbeRequest extends BaseRequest
{
    use JsonResponses;
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:probes'],
            'description' => 'required',
            'probe_type_id' => 'required|exists:probe_types,id',
        ];
    }
}
