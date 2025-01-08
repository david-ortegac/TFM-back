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

    public function updateProfile(): JsonResponse
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'. auth()->id(),
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error.', $validator->errors(), ResponseAlias::HTTP_BAD_REQUEST);
        }

        $user = User::find(auth()->id());
        $user->update(request()->all());

        return $this->sendResponse($user, 'Profile updated successfully.');
    }

    public function findByEmail(): JsonResponse
    {
        $validator = Validator::make(request()->all(), [
            'email' =>'required|email'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error.', $validator->errors(), ResponseAlias::HTTP_BAD_REQUEST);
        }

        $user = User::where('email', request()->email)->first();

        if (!$user) {
            return $this->sendError('User not found.', [], ResponseAlias::HTTP_NOT_FOUND);
        }

        return $this->sendResponse($user, 'User found successfully.');
    }

    public function changeUserType(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'id'=>'required',
            'type' =>'required|in:admin,seller,user'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error.', $validator->errors(), ResponseAlias::HTTP_BAD_REQUEST);
        }

        $user = User::findOrFail(auth()->id());
        $user->type = $request->type;
        $user->save();

        return $this->sendResponse($user, 'User type updated successfully.');
    }

    public function logout(): JsonResponse
    {
        return $this->sendResponse(auth()->logout(), 'Successfully logged out');
    }

    public function deleteProfile(): JsonResponse
    {

        $user = User::find(auth()->id());
        $user->delete();

        return $this->sendResponse([], 'Profile deleted successfully.');
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
