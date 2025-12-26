<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class FeatureTestCase extends TestCase
{
    use DatabaseTransactions, InteractsWithDatabase;

    /**
     * A basic feature test example.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed('Database\Seeders\OnDemandSeeder');
    }

    /**
     * @param  array<string, string>  $headers
     * @return $this
     */
    protected function request(array $headers = []): static
    {
        return $this->withHeaders([
            'access-token' => config('services.access_token'),
            ...$headers,
        ]);
    }
}
