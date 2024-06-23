<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Actions\User\CreateUserAction;
use App\Http\Requests\V1\LoginUserRequest;
use App\Http\Requests\V1\StoreUserRequest;
use App\Http\Resources\V1\UserResource;
use App\Traits\JsonResponses;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

final class RegisterController
{
    use JsonResponses;
    public function register(StoreUserRequest $request, CreateUserAction $createUserAction): JsonResponse
    {
        $user = $createUserAction->handle(
            data: $request->only('name', 'email', 'password'),
        );

        return $this->successResponse(
            data: new UserResource($user),
            code :Response::HTTP_CREATED,
        );
    }

    public function login(LoginUserRequest $request): JsonResponse
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            return $this->successResponse(
                data: new UserResource($user),
            );
        }

        throw new HttpResponseException(
            $this->errorResponse(
                'Unauthorised',
                Response::HTTP_UNAUTHORIZED,
            ),
        );
    }
}
