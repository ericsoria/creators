<?php

namespace Tests\Feature\Api;

use App\Models\Brand;
use App\Models\City;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BrandCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_brand_with_relationships(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $city = City::factory()->create();
        $tag = Tag::factory()->create();

        $response = $this->postJson('/api/v1/brands', [
            'name' => 'Restaurant X',
            'slug' => 'restaurant-x',
            'industry' => 'food',
            'description' => 'A restaurant brand.',
            'website_url' => 'https://example.com',
            'status' => 'active',
            'notes' => 'Important account.',
            'city_ids' => [$city->id],
            'tag_ids' => [$tag->id],
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.name', 'Restaurant X')
            ->assertJsonPath('data.cities.0.id', $city->id)
            ->assertJsonPath('data.tags.0.id', $tag->id);

        $brand = Brand::query()->firstOrFail();
        $this->assertTrue($brand->cities()->whereKey($city->id)->exists());
        $this->assertTrue($brand->tags()->whereKey($tag->id)->exists());
    }

    public function test_brand_validation_rejects_unknown_relationship_ids(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/v1/brands', [
            'name' => 'Restaurant X',
            'slug' => 'restaurant-x',
            'city_ids' => [999],
            'tag_ids' => [999],
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['city_ids.0', 'tag_ids.0']);
    }

    public function test_authenticated_user_can_update_show_filter_and_delete_brands(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $city = City::factory()->create();
        $tag = Tag::factory()->create();
        $matching = Brand::factory()->create(['status' => 'active']);
        $other = Brand::factory()->create(['status' => 'paused']);
        $matching->cities()->attach($city);
        $matching->tags()->attach($tag);

        $this->patchJson("/api/v1/brands/{$matching->id}", [
            'name' => 'Updated Brand',
            'city_ids' => [$city->id],
            'tag_ids' => [$tag->id],
        ])
            ->assertOk()
            ->assertJsonPath('data.name', 'Updated Brand')
            ->assertJsonPath('data.cities.0.id', $city->id)
            ->assertJsonPath('data.tags.0.id', $tag->id);

        $this->getJson("/api/v1/brands/{$matching->id}")
            ->assertOk()
            ->assertJsonPath('data.name', 'Updated Brand');

        $this->getJson("/api/v1/brands?status=active&city={$city->id}&tag={$tag->id}")
            ->assertOk()
            ->assertJsonPath('data.0.id', $matching->id)
            ->assertJsonMissing(['id' => $other->id])
            ->assertJsonStructure(['data', 'meta', 'links']);

        $this->deleteJson("/api/v1/brands/{$matching->id}")
            ->assertNoContent();

        $this->assertSoftDeleted($matching);
        $this->getJson('/api/v1/brands')->assertJsonMissing(['id' => $matching->id]);
    }
}
