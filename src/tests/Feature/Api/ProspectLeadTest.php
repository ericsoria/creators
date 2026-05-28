<?php

namespace Tests\Feature\Api;

use App\Models\Brand;
use App\Models\Prospect;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProspectLeadTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_update_filter_and_delete_prospects(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson('/api/v1/prospects', [
            'prospect_type' => Prospect::TYPE_CREATOR,
            'platform' => 'instagram',
            'handle' => 'food_creator',
            'profile_url' => 'https://instagram.com/food_creator',
            'name' => 'Food Creator',
            'category' => 'food',
            'status' => Prospect::STATUS_DISCOVERED,
            'source' => 'manual',
        ])->assertCreated();

        $prospectId = $response->json('data.id');

        $this->patchJson("/api/v1/prospects/{$prospectId}", [
            'status' => Prospect::STATUS_CONTACTED,
            'contacted_at' => '2026-05-26T10:00:00Z',
        ])
            ->assertOk()
            ->assertJsonPath('data.status', Prospect::STATUS_CONTACTED);

        Prospect::factory()->brand()->create(['status' => Prospect::STATUS_REJECTED, 'platform' => 'tiktok']);

        $this->getJson('/api/v1/prospects?prospect_type=creator&status=contacted&platform=instagram&category=food&source=manual&contacted_at=2026-05-26')
            ->assertOk()
            ->assertJsonPath('data.0.id', $prospectId)
            ->assertJsonStructure(['data', 'meta', 'links']);

        $this->deleteJson("/api/v1/prospects/{$prospectId}")->assertNoContent();
        $this->assertSoftDeleted(Prospect::class, ['id' => $prospectId]);
    }

    public function test_authenticated_user_can_create_brand_prospect(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/v1/prospects', [
            'prospect_type' => Prospect::TYPE_BRAND,
            'platform' => 'instagram',
            'handle' => 'demo_restaurant',
            'profile_url' => 'https://instagram.com/demo_restaurant',
            'name' => 'Demo Restaurant',
            'category' => 'restaurant',
            'status' => Prospect::STATUS_DISCOVERED,
            'source' => 'manual',
        ])
            ->assertCreated()
            ->assertJsonPath('data.prospect_type', Prospect::TYPE_BRAND)
            ->assertJsonPath('data.category', 'restaurant');
    }

    public function test_prospect_validation_rejects_invalid_type_status_and_filter(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/v1/prospects', [
            'prospect_type' => 'agency',
            'platform' => 'instagram',
            'handle' => 'bad_type',
            'status' => 'bad',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['prospect_type', 'status']);

        $this->getJson('/api/v1/prospects?prospect_type=agency')
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['prospect_type']);
    }

    public function test_authenticated_user_can_approve_creator_prospect(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $prospect = Prospect::factory()->creator()->create([
            'platform' => 'instagram',
            'handle' => 'approved_creator',
            'profile_url' => 'https://instagram.com/approved_creator',
            'status' => Prospect::STATUS_INTERESTED,
        ]);

        $this->postJson("/api/v1/prospects/{$prospect->id}/approve-as-creator", [
            'name' => 'Approved Creator',
            'email' => 'approved@example.com',
        ])
            ->assertCreated()
            ->assertJsonPath('data.name', 'Approved Creator')
            ->assertJsonPath('data.social_accounts.0.platform', 'instagram')
            ->assertJsonPath('data.social_accounts.0.handle', 'approved_creator');

        $prospect->refresh();

        $this->assertSame(Prospect::STATUS_APPROVED, $prospect->status);
        $this->assertNotNull($prospect->approved_at);

        $this->postJson("/api/v1/prospects/{$prospect->id}/approve-as-creator")
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['prospect']);
    }

    public function test_creator_approval_rejects_non_creator_prospect(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $prospect = Prospect::factory()->brand()->create(['status' => Prospect::STATUS_INTERESTED]);

        $this->postJson("/api/v1/prospects/{$prospect->id}/approve-as-creator")
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['prospect']);
    }

    public function test_authenticated_user_can_approve_brand_prospect(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $prospect = Prospect::factory()->brand()->create([
            'platform' => 'instagram',
            'handle' => 'approved_brand',
            'profile_url' => 'https://instagram.com/approved_brand',
            'name' => 'Approved Brand',
            'category' => 'restaurant',
            'status' => Prospect::STATUS_INTERESTED,
        ]);

        $this->postJson("/api/v1/prospects/{$prospect->id}/approve-as-brand", [
            'website_url' => 'https://approved-brand.test',
        ])
            ->assertCreated()
            ->assertJsonPath('data.name', 'Approved Brand')
            ->assertJsonPath('data.slug', 'approved-brand')
            ->assertJsonPath('data.industry', 'restaurant')
            ->assertJsonPath('data.social_accounts.0.platform', 'instagram')
            ->assertJsonPath('data.social_accounts.0.handle', 'approved_brand');

        $prospect->refresh();

        $this->assertSame(Prospect::STATUS_APPROVED, $prospect->status);
        $this->assertNotNull($prospect->approved_at);
    }

    public function test_brand_approval_rejects_non_brand_and_duplicate_slug(): void
    {
        Sanctum::actingAs(User::factory()->create());
        Brand::factory()->create(['slug' => 'existing-brand']);

        $creatorProspect = Prospect::factory()->creator()->create(['status' => Prospect::STATUS_INTERESTED]);
        $brandProspect = Prospect::factory()->brand()->create(['status' => Prospect::STATUS_INTERESTED]);

        $this->postJson("/api/v1/prospects/{$creatorProspect->id}/approve-as-brand")
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['prospect']);

        $this->postJson("/api/v1/prospects/{$brandProspect->id}/approve-as-brand", ['slug' => 'existing-brand'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['slug']);
    }

    public function test_prospect_endpoints_reject_unauthenticated_requests(): void
    {
        $this->getJson('/api/v1/prospects')
            ->assertUnauthorized()
            ->assertJson(['message' => 'Unauthenticated.']);
    }
}
