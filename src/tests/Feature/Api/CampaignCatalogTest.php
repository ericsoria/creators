<?php

namespace Tests\Feature\Api;

use App\Models\Brand;
use App\Models\Campaign;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CampaignCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_campaign_with_tags(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $brand = Brand::factory()->create();
        $tag = Tag::factory()->create();

        $this->postJson('/api/v1/campaigns', [
            'brand_id' => $brand->id,
            'name' => 'May Creator Visits',
            'description' => 'Visits in May.',
            'objective' => 'Generate content.',
            'status' => Campaign::STATUS_ACTIVE,
            'starts_at' => '2026-05-01T10:00:00Z',
            'ends_at' => '2026-05-31T10:00:00Z',
            'compensation_type' => 'barter',
            'requirements' => 'One post.',
            'notes' => 'Priority.',
            'tag_ids' => [$tag->id],
        ])
            ->assertCreated()
            ->assertJsonPath('data.brand_id', $brand->id)
            ->assertJsonPath('data.name', 'May Creator Visits')
            ->assertJsonPath('data.status', Campaign::STATUS_ACTIVE)
            ->assertJsonPath('data.tags.0.id', $tag->id);
    }

    public function test_campaign_validation_rejects_missing_brand_and_unsupported_status(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/v1/campaigns', [
            'name' => 'Invalid Campaign',
            'status' => 'unsupported',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['brand_id', 'status']);
    }

    public function test_authenticated_user_can_update_show_filter_and_delete_campaigns(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $brand = Brand::factory()->create();
        $otherBrand = Brand::factory()->create();
        $tag = Tag::factory()->create();
        $matching = Campaign::factory()->for($brand)->create([
            'status' => Campaign::STATUS_ACTIVE,
            'starts_at' => '2026-05-01 00:00:00',
            'ends_at' => '2026-05-31 00:00:00',
        ]);
        $other = Campaign::factory()->for($otherBrand)->create(['status' => Campaign::STATUS_DRAFT]);
        $matching->tags()->attach($tag);

        $this->patchJson("/api/v1/campaigns/{$matching->id}", [
            'name' => 'Updated Campaign',
            'status' => Campaign::STATUS_PAUSED,
            'tag_ids' => [$tag->id],
        ])
            ->assertOk()
            ->assertJsonPath('data.name', 'Updated Campaign')
            ->assertJsonPath('data.status', Campaign::STATUS_PAUSED)
            ->assertJsonPath('data.tags.0.id', $tag->id);

        $this->getJson("/api/v1/campaigns/{$matching->id}")
            ->assertOk()
            ->assertJsonPath('data.name', 'Updated Campaign')
            ->assertJsonPath('data.brand.id', $brand->id);

        $this->getJson("/api/v1/campaigns?status=paused&brand={$brand->id}&tag={$tag->id}&starts_at=2026-05-01&ends_at=2026-05-31")
            ->assertOk()
            ->assertJsonPath('data.0.id', $matching->id)
            ->assertJsonMissing(['id' => $other->id])
            ->assertJsonStructure(['data', 'meta', 'links']);

        $this->deleteJson("/api/v1/campaigns/{$matching->id}")
            ->assertNoContent();

        $this->assertSoftDeleted($matching);
        $this->getJson('/api/v1/campaigns')->assertJsonMissing(['id' => $matching->id]);
    }
}
