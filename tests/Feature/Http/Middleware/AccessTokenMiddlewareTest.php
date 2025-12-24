<?php

namespace Tests\Feature\Http\Middleware;

use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AccessTokenMiddlewareTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    #[Test]
    public function it_return_unauthorized_when_the_token_does_not_exist(): void
    {
        $password = 'password';
        $user = User::factory()->create([
            'password' => $password,
        ]
        );
        $response = $this->post(route('api.auth.login'), [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
