<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Models\User;
use App\Traits\JsonResponses;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

final class RegisterController
{
    use JsonResponses;
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            throw new HttpResponseException(
                $this->errorResponse($validator->errors(), Response::HTTP_BAD_REQUEST),
            );
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $user->assignRole('user');
        $success['token'] = $user->createToken('MyApp')->plainTextToken;
        $success['roles'] = $user->getRoleNames();
        $success['name'] = $user->name;

        return $this->successResponse($success, Response::HTTP_CREATED);
    }

    public function login(Request $request): JsonResponse
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('MyApp')->plainTextToken;
            $success['roles'] = $user->getRoleNames();
            $success['name'] = $user->name;

            return $this->successResponse($success);
        }
        throw new HttpResponseException(
            $this->errorResponse(
                'Unauthorised',
                Response::HTTP_UNAUTHORIZED,
            ),
        );
    }
}
