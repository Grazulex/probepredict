<?php

declare(strict_types=1);

namespace App\Http\Requests\V1;

use App\Traits\JsonResponses;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class BaseRequest extends FormRequest
{
    use JsonResponses;
    protected function failedValidation(Validator $validator): void
    {
        $errors = $validator->errors();
        $response = $this->errorResponse(
            message: $errors,
            code: Response::HTTP_UNPROCESSABLE_ENTITY,
        );

        throw new HttpResponseException($response);
    }
}
