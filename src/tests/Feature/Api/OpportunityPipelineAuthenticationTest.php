<?php

namespace Tests\Feature\Api;

use App\Models\Opportunity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OpportunityPipelineAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_opportunity_endpoints_reject_unauthenticated_requests(): void
    {
        $opportunity = Opportunity::factory()->create();

        $this->getJson('/api/v1/opportunities')->assertUnauthorized();
        $this->postJson('/api/v1/opportunities')->assertUnauthorized();
        $this->getJson("/api/v1/opportunities/{$opportunity->id}")->assertUnauthorized();
        $this->patchJson("/api/v1/opportunities/{$opportunity->id}")->assertUnauthorized();
        $this->deleteJson("/api/v1/opportunities/{$opportunity->id}")->assertUnauthorized();
        $this->postJson("/api/v1/opportunities/{$opportunity->id}/accept")->assertUnauthorized();
        $this->getJson("/api/v1/opportunities/{$opportunity->id}/events")->assertUnauthorized();
        $this->postJson("/api/v1/opportunities/{$opportunity->id}/events")->assertUnauthorized();
    }
}
