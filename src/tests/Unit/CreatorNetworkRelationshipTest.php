<?php

namespace Tests\Unit;

use App\Models\Brand;
use App\Models\City;
use App\Models\Creator;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreatorNetworkRelationshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_creator_taxonomy_and_polymorphic_social_account_relationships_work(): void
    {
        $creator = Creator::factory()->create();
        $brand = Brand::factory()->create();
        $city = City::factory()->create();
        $tag = Tag::factory()->create();

        $creator->cities()->attach($city);
        $creator->tags()->attach($tag);
        $creator->socialAccounts()->create(['platform' => 'instagram', 'handle' => 'creator_handle']);
        $brand->socialAccounts()->create(['platform' => 'instagram', 'handle' => 'brand_handle']);

        $this->assertTrue($creator->cities()->whereKey($city->id)->exists());
        $this->assertTrue($creator->tags()->whereKey($tag->id)->exists());
        $this->assertTrue($creator->socialAccounts()->where('handle', 'creator_handle')->exists());
        $this->assertTrue($brand->socialAccounts()->where('handle', 'brand_handle')->exists());
    }
}
