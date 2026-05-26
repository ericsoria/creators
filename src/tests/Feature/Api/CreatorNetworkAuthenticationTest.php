<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreatorNetworkAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_creator_network_endpoints_reject_unauthenticated_requests(): void
    {
        foreach (['/api/v1/creator-leads', '/api/v1/creators', '/api/v1/social-accounts'] as $endpoint) {
            $this->getJson($endpoint)
                ->assertUnauthorized()
                ->assertJson(['message' => 'Unauthenticated.']);
        }
    }
}
