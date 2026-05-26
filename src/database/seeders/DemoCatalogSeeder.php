<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Campaign;
use App\Models\City;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class DemoCatalogSeeder extends Seeder
{
    /**
     * Seed optional demo catalog data.
     */
    public function run(): void
    {
        if (! app()->environment('local')) {
            return;
        }

        $brand = Brand::query()->firstOrCreate(
            ['slug' => 'demo-restaurant'],
            [
                'name' => 'Demo Restaurant',
                'industry' => 'food',
                'description' => 'Demo brand for local catalog exploration.',
                'website_url' => 'https://example.com',
                'status' => 'active',
            ],
        );

        $brand->cities()->syncWithoutDetaching(City::query()->where('slug', 'barcelona')->pluck('id'));
        $brand->tags()->syncWithoutDetaching(Tag::query()->whereIn('slug', ['food', 'lifestyle'])->pluck('id'));

        Campaign::query()->firstOrCreate(
            ['brand_id' => $brand->id, 'name' => 'Demo Creator Visits'],
            [
                'description' => 'Demo campaign for creator visits.',
                'objective' => 'Generate local social content.',
                'status' => Campaign::STATUS_DRAFT,
                'compensation_type' => 'barter',
                'requirements' => 'One visit and one publication.',
            ],
        );
    }
}
