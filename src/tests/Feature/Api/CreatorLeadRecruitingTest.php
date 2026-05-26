<?php

namespace Tests\Feature\Api;

use App\Models\CreatorLead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreatorLeadRecruitingTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_update_filter_and_delete_creator_leads(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson('/api/v1/creator-leads', [
            'platform' => 'instagram',
            'handle' => 'food_creator',
            'profile_url' => 'https://instagram.com/food_creator',
            'name' => 'Food Creator',
            'niche' => 'food',
            'status' => CreatorLead::STATUS_DISCOVERED,
            'source' => 'manual',
        ])->assertCreated();

        $leadId = $response->json('data.id');

        $this->patchJson("/api/v1/creator-leads/{$leadId}", [
            'status' => CreatorLead::STATUS_CONTACTED,
            'contacted_at' => '2026-05-26T10:00:00Z',
        ])
            ->assertOk()
            ->assertJsonPath('data.status', CreatorLead::STATUS_CONTACTED);

        CreatorLead::factory()->create(['status' => CreatorLead::STATUS_REJECTED, 'platform' => 'tiktok']);

        $this->getJson('/api/v1/creator-leads?status=contacted&platform=instagram&niche=food&source=manual&contacted_at=2026-05-26')
            ->assertOk()
            ->assertJsonPath('data.0.id', $leadId)
            ->assertJsonStructure(['data', 'meta', 'links']);

        $this->deleteJson("/api/v1/creator-leads/{$leadId}")->assertNoContent();
        $this->assertSoftDeleted(CreatorLead::class, ['id' => $leadId]);
    }

    public function test_creator_lead_validation_rejects_invalid_status(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/v1/creator-leads', [
            'platform' => 'instagram',
            'handle' => 'bad_status',
            'status' => 'bad',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['status']);
    }

    public function test_authenticated_user_can_approve_creator_lead(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $lead = CreatorLead::factory()->create([
            'platform' => 'instagram',
            'handle' => 'approved_creator',
            'profile_url' => 'https://instagram.com/approved_creator',
            'status' => CreatorLead::STATUS_INTERESTED,
        ]);

        $this->postJson("/api/v1/creator-leads/{$lead->id}/approve", [
            'name' => 'Approved Creator',
            'email' => 'approved@example.com',
        ])
            ->assertCreated()
            ->assertJsonPath('data.name', 'Approved Creator')
            ->assertJsonPath('data.social_accounts.0.platform', 'instagram')
            ->assertJsonPath('data.social_accounts.0.handle', 'approved_creator');

        $lead->refresh();

        $this->assertSame(CreatorLead::STATUS_APPROVED, $lead->status);
        $this->assertNotNull($lead->approved_at);

        $this->postJson("/api/v1/creator-leads/{$lead->id}/approve")
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['creator_lead']);
    }
}
