<?php

namespace Tests\Security;

use App\Models\User;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    public function test_sql_injection()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/api/users?search=1 OR 1=1');

        $response->assertStatus(200);
        $this->assertDatabaseCount('users', 1);
    }

    public function test_xss_protection()
    {
        $user = User::factory()->create(['name' => '<script>alert("XSS")</script>']);
        $response = $this->actingAs($user)->get('/profile');

        $response->assertDontSee('<script>alert("XSS")</script>', false);
    }

    public function test_rate_limiting()
    {
        $user = User::factory()->create();

        // Perform 60 requests to reach the rate limit
        for ($i = 0; $i < 60; $i++) {
            $response = $this->actingAs($user, 'sanctum')->get('/api/users');
            $response->assertStatus(200);
        }

        // The 61st request should fail with a 429 status code
        $response = $this->actingAs($user, 'sanctum')->get('/api/users');
        $response->assertStatus(429); // Too many requests
    }

    public function test_secure_headers()
    {
        $response = $this->get('/api/versions/base');

        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'DENY');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeader('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        $response->assertHeader('Referrer-Policy', 'no-referrer-when-downgrade');
        $response->assertHeader('Content-Security-Policy', "default-src 'self'");
    }
}
