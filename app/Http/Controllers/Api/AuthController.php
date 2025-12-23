<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    const string ERROR_MESSAGE = 'Invalid credentials provided.';

    /**
     * Handle the incoming request.
     */
    public function register(RegisterRequest $request)
    {
        dd($request->all());
    }

    public function login(LoginRequest $request)
    {
        $fields = $request->validated();

        if (! auth()->attempt($fields)) {
            return response()->json([
                'status' => 'error',
                'data' => [
                    'message' => self::ERROR_MESSAGE,
                ],
            ], 422);
        }
        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken(
            'authToken',
            [],
            now()->addHours(2)
        );

        return response()->json([
            'status' => 'success',
            'data' => [
                'token' => $token->plainTextToken,
            ],
        ]);

    }
}
