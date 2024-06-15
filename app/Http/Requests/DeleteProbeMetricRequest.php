<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Http\Controllers\API\BaseController;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class DeleteProbeMetricRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->currentTeam->id === $this->probeMetrics->probe->team_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [

        ];
    }

    protected function failedAuthorization(): void
    {
        $base = new BaseController();
        $response = $base->sendError(
            error: 'You are not authorized to delete this metric.',
            status: Response::HTTP_UNAUTHORIZED,
        );

        throw new HttpResponseException($response);
    }
}
