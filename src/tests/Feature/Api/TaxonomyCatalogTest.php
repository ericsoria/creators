<?php

namespace Tests\Feature\Api;

use App\Models\City;
use App\Models\Tag;
use App\Models\User;
use Database\Seeders\TaxonomySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TaxonomyCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_list_cities(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $city = City::factory()->create(['name' => 'Barcelona', 'slug' => 'barcelona']);

        $this->getJson('/api/v1/cities')
            ->assertOk()
            ->assertJsonPath('data.0.id', $city->id)
            ->assertJsonPath('data.0.name', 'Barcelona')
            ->assertJsonPath('data.0.slug', 'barcelona');
    }

    public function test_authenticated_user_can_create_and_list_tags(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/v1/tags', [
            'name' => 'Food',
            'slug' => 'food',
            'type' => 'niche',
        ])
            ->assertCreated()
            ->assertJsonPath('data.name', 'Food')
            ->assertJsonPath('data.slug', 'food')
            ->assertJsonPath('data.type', 'niche');

        $this->getJson('/api/v1/tags')
            ->assertOk()
            ->assertJsonPath('data.0.slug', 'food');

        $this->assertDatabaseHas('tags', ['slug' => 'food', 'type' => 'niche']);
    }

    public function test_tag_creation_requires_valid_data(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/v1/tags', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'slug', 'type']);
    }

    public function test_taxonomy_seeder_is_idempotent(): void
    {
        $this->seed(TaxonomySeeder::class);
        $this->seed(TaxonomySeeder::class);

        $this->assertSame(1, City::query()->where('slug', 'barcelona')->count());
        $this->assertSame(1, Tag::query()->where('slug', 'food')->where('type', 'niche')->count());
    }
}
