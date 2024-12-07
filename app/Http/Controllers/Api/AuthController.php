<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json($user, 201);
    }

    public function login(): JsonResponse
    {

        if (!$token = auth('api')->attempt([
            "email"     => request()->email,
            "password"  => request()->password,
        ])) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function getUser(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }

    protected function respondWithToken($token): JsonResponse
    {
        $user_id = auth('api')->user()->id;
        $user = User::with('role')->findOrFail($user_id);
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'user'         => $user
        ]);
    }
}
