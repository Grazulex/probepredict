<?php

declare(strict_types=1);

namespace App\Http\Requests\V1;

use App\Traits\JsonResponses;

class StoreUserRequest extends BaseRequest
{
    use JsonResponses;
    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ];
    }
}
