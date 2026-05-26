<?php

namespace Tests\Unit;

use App\Models\Brand;
use App\Models\Campaign;
use App\Models\City;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogRelationshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_brand_and_campaign_taxonomy_relationships_work(): void
    {
        $city = City::factory()->create();
        $brandTag = Tag::factory()->create();
        $campaignTag = Tag::factory()->create(['type' => 'content']);
        $brand = Brand::factory()->create();
        $campaign = Campaign::factory()->for($brand)->create();

        $brand->cities()->attach($city);
        $brand->tags()->attach($brandTag);
        $campaign->tags()->attach($campaignTag);

        $this->assertTrue($brand->cities()->whereKey($city->id)->exists());
        $this->assertTrue($brand->tags()->whereKey($brandTag->id)->exists());
        $this->assertTrue($campaign->tags()->whereKey($campaignTag->id)->exists());
        $this->assertTrue($brand->campaigns()->whereKey($campaign->id)->exists());
    }
}
