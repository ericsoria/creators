<?php

namespace Tests\Feature\Api;

use App\Models\City;
use App\Models\Creator;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreatorNetworkTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_update_filter_and_delete_creators(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $city = City::factory()->create();
        $tag = Tag::factory()->create();

        $response = $this->postJson('/api/v1/creators', [
            'name' => 'Creator One',
            'username' => 'creator_one',
            'email' => 'creator@example.com',
            'ugc_only' => true,
            'accepts_barter' => true,
            'status' => Creator::STATUS_ACTIVE,
            'rating' => 5,
            'city_ids' => [$city->id],
            'tag_ids' => [$tag->id],
        ])->assertCreated();

        $creatorId = $response->json('data.id');

        $this->patchJson("/api/v1/creators/{$creatorId}", [
            'name' => 'Creator Updated',
            'status' => Creator::STATUS_PAUSED,
            'city_ids' => [$city->id],
            'tag_ids' => [$tag->id],
        ])
            ->assertOk()
            ->assertJsonPath('data.name', 'Creator Updated')
            ->assertJsonPath('data.cities.0.id', $city->id)
            ->assertJsonPath('data.tags.0.id', $tag->id);

        Creator::factory()->create(['status' => Creator::STATUS_INACTIVE, 'ugc_only' => false]);

        $this->getJson("/api/v1/creators?status=paused&city={$city->id}&tag={$tag->id}&ugc_only=1&accepts_barter=1&search=Updated")
            ->assertOk()
            ->assertJsonPath('data.0.id', $creatorId)
            ->assertJsonStructure(['data', 'meta', 'links']);

        $this->deleteJson("/api/v1/creators/{$creatorId}")->assertNoContent();
        $this->assertSoftDeleted(Creator::class, ['id' => $creatorId]);
    }

    public function test_creator_validation_rejects_unknown_taxonomy_and_status(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/v1/creators', [
            'name' => 'Bad Creator',
            'status' => 'bad',
            'city_ids' => [999],
            'tag_ids' => [999],
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['status', 'city_ids.0', 'tag_ids.0']);
    }
}
