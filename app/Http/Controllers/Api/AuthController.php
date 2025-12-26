<?php

namespace App\Http\Controllers\Api;

use App\Enums\RolesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\RegisteredUserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;

#[Group('Authentication', 'API for user authentication and registration.')]
#[Header('access-token', 'The access token for authentication')]
class AuthController extends Controller
{
    const string ERROR_MESSAGE = 'Invalid credentials provided.';

    /**
     * Handle the incoming request.
     */
    #[Endpoint('Register a new user', 'Registers a new user and returns an authentication token.')]
    #[BodyParam('password_confirmation', 'string', 'The password confirmation field must match the password field.', required: true)]
    public function register(RegisterRequest $request)
    {
        $fields = $request->validated();
        $user = User::create($fields);
        $user->assignRole(RolesEnum::USER);

        return new RegisteredUserResource($user);
    }

    #[Endpoint('Login a user', 'Authenticates a user and returns an authentication token.')]
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
