<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoginController extends Controller
{

    public function __invoke(): JsonResponse
    {
        $credentials = request(['email', 'password']);

        if(! $token = auth(guard:'api')->attempt($credentials)){
            return response()->json(['error'=>'Unauthorized'], 401);
        }

        return response()->json([
            'token' => $token
        ]);

    }
}
