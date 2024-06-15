<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Http\Controllers\API\BaseController;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class StoreProbeRuleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation$messages rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'probe_id' => ['required', 'exists:probes,id,team_id,' . $this->user()->currentTeam->id],
            'metric_type_id' => ['required', 'exists:metric_types,id'],
            'operator' => ['required'],
            'condition' => ['required'],
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        $errors = $validator->errors();
        $base = new BaseController();
        $response = $base->sendError(
            error: 'Validation Error.',
            messages: $errors,
            status: Response::HTTP_UNPROCESSABLE_ENTITY,
        );

        throw new HttpResponseException($response);
    }
}
