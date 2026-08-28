<?php

namespace Tests\Feature;

use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_responses_include_security_headers(): void
    {
        Profile::query()->create([
            'full_name' => 'Ivan Alvarez Valencia',
            'headline' => 'Ingeniero de Software',
            'introduction' => 'Perfil profesional de prueba.',
            'is_published' => true,
        ]);

        $response = $this->get(
            route('home')
        );

        $response
            ->assertOk()
            ->assertHeader(
                'X-Content-Type-Options',
                'nosniff'
            )
            ->assertHeader(
                'X-Frame-Options',
                'SAMEORIGIN'
            )
            ->assertHeader(
                'Referrer-Policy',
                'strict-origin-when-cross-origin'
            )
            ->assertHeader(
                'Permissions-Policy',
                'camera=(), microphone=(), geolocation=()'
            )
            ->assertHeader(
                'Content-Security-Policy',
                "base-uri 'self'; object-src 'none'; frame-ancestors 'self'"
            );
    }
}
