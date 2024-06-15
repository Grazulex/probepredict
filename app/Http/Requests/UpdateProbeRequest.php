<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Http\Controllers\API\BaseController;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class UpdateProbeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->currentTeam->id === $this->probe->team_id;
    }

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

    protected function failedAuthorization(): void
    {
        $base = new BaseController();
        $response = $base->sendError(
            error: 'You are not authorized to update this probe.',
            status: Response::HTTP_UNAUTHORIZED,
        );

        throw new HttpResponseException($response);
    }
}
