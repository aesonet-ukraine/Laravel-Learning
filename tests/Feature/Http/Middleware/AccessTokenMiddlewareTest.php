<?php

namespace Tests\Feature\Http\Middleware;

use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AccessTokenMiddlewareTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $password = 'password';
        $user = User::factory()->create([
            'password' => $password,
        ]
        );
        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
