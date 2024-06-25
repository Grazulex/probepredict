<?php

declare(strict_types=1);

namespace App\Http\Requests\V1;

use App\Traits\JsonResponses;

class LoginUserRequest extends BaseRequest
{
    use JsonResponses;
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ];
    }
}
