<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;


class AuthController extends BaseController
{
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error.', $validator->errors(), ResponseAlias::HTTP_BAD_REQUEST);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        $success['user'] = $user;

        return $this->sendResponse($success, 'User registered successfully.');
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return JsonResponse
     */
    public function login(): JsonResponse
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return $this->sendError('Invalid credentials', ['error' => 'Unauthenticated'], ResponseAlias::HTTP_UNAUTHORIZED);
        }

        $success = $this->respondWithToken($token);

        return $this->sendResponse($success, 'User logged successfully.');
    }

    public function profile(): JsonResponse
    {
        return $this->sendResponse(auth()->user(), 'Profile information');
    }


    public function logout(): JsonResponse
    {
        return $this->sendResponse(auth()->logout(), 'Successfully logged out');
    }

    public function refresh(): JsonResponse
    {
        return $this->sendResponse($this->respondWithToken(auth()->refresh()), 'New Token Generated');
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return JsonResponse
     */
    protected function respondWithToken($token): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];
    }
}
