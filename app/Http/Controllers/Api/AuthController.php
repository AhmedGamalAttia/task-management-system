<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;


class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (!auth()->attempt($credentials)) {
                return errorResponse('Invalid credentials', 401);
            }

            $user = User::where('email', $request->email)->first();

            $token = $user->createToken('auth_token')->plainTextToken;

            return successResponse([
                'user' => new UserResource($user),
                'token' => $token,
            ], 'User logged in successfully');
        } catch (\Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return successResponse(null, 'User logged out successfully');
        } catch (\Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }

    public function me(Request $request)
    {
        return successResponse(new UserResource($request->user()), 'User retrieved successfully');
    }
}
