<?php

namespace Tests\Feature\Api;

use App\Models\Brand;
use App\Models\Creator;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SocialAccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_manage_creator_social_accounts_and_primary_behavior(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $creator = Creator::factory()->create();

        $first = $this->postJson('/api/v1/social-accounts', [
            'accountable_type' => Creator::class,
            'accountable_id' => $creator->id,
            'platform' => 'instagram',
            'handle' => 'first_handle',
            'url' => 'https://instagram.com/first_handle',
            'is_primary' => true,
        ])->assertCreated()->json('data.id');

        $second = $this->postJson('/api/v1/social-accounts', [
            'accountable_type' => Creator::class,
            'accountable_id' => $creator->id,
            'platform' => 'instagram',
            'handle' => 'second_handle',
            'is_primary' => true,
        ])->assertCreated()->json('data.id');

        $this->assertFalse(SocialAccount::query()->findOrFail($first)->is_primary);
        $this->assertTrue(SocialAccount::query()->findOrFail($second)->is_primary);

        $this->getJson("/api/v1/creators/{$creator->id}/social-accounts")
            ->assertOk()
            ->assertJsonCount(2, 'data');

        $this->getJson('/api/v1/social-accounts?platform=instagram&accountable_type='.urlencode(Creator::class).'&accountable_id='.$creator->id)
            ->assertOk()
            ->assertJsonStructure(['data', 'meta', 'links']);

        $this->patchJson("/api/v1/social-accounts/{$second}", ['handle' => 'updated_handle'])
            ->assertOk()
            ->assertJsonPath('data.handle', 'updated_handle');

        $this->deleteJson("/api/v1/social-accounts/{$second}")->assertNoContent();
        $this->assertSoftDeleted(SocialAccount::class, ['id' => $second]);
    }

    public function test_authenticated_user_can_create_brand_social_account(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $brand = Brand::factory()->create();

        $this->postJson('/api/v1/social-accounts', [
            'accountable_type' => Brand::class,
            'accountable_id' => $brand->id,
            'platform' => 'tiktok',
            'handle' => 'brand_handle',
        ])->assertCreated();

        $this->getJson("/api/v1/brands/{$brand->id}/social-accounts")
            ->assertOk()
            ->assertJsonPath('data.0.handle', 'brand_handle');
    }

    public function test_social_account_validation_rejects_invalid_owner(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/v1/social-accounts', [
            'accountable_type' => Creator::class,
            'accountable_id' => 999,
            'platform' => 'instagram',
            'handle' => 'missing_owner',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['accountable_id']);
    }
}
