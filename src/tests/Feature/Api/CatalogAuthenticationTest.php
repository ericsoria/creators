<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_endpoints_reject_unauthenticated_requests(): void
    {
        foreach (['/api/v1/cities', '/api/v1/tags', '/api/v1/brands', '/api/v1/campaigns'] as $endpoint) {
            $this->getJson($endpoint)
                ->assertUnauthorized()
                ->assertJson(['message' => 'Unauthenticated.']);
        }
    }
}
