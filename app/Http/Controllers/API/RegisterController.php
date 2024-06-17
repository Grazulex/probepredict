<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

final class RegisterController extends BaseController
{
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
                $this->sendError(
                    error: 'Validation Error.',
                    messages: $validator->errors(),
                    status: Response::HTTP_BAD_REQUEST,
                ),
            );
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $user->assignRole('user');
        $success['token'] = $user->createToken('MyApp')->plainTextToken;
        $success['roles'] = $user->getRoleNames();
        $success['name'] = $user->name;

        return $this->sendResponse($success, 'User register successfully.', 201);
    }

    public function login(Request $request): JsonResponse
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('MyApp')->plainTextToken;
            $success['roles'] = $user->getRoleNames();
            $success['name'] = $user->name;

            return $this->sendResponse($success, 'User login successfully.');
        }
        throw new HttpResponseException(
            $this->sendError(
                error: 'Unauthorised.',
                messages: ['error' => 'Unauthorised'],
                status: Response::HTTP_UNAUTHORIZED,
            ),
        );
    }
}
