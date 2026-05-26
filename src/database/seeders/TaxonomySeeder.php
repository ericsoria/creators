<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class  TaxonomySeeder extends Seeder
{
    /**
     * Seed taxonomy data.
     */
    public function run(): void
    {
        collect([
            ['name' => 'Barcelona', 'slug' => 'barcelona', 'country' => 'ES', 'timezone' => 'Europe/Madrid'],
            ['name' => 'Madrid', 'slug' => 'madrid', 'country' => 'ES', 'timezone' => 'Europe/Madrid'],
            ['name' => 'Valencia', 'slug' => 'valencia', 'country' => 'ES', 'timezone' => 'Europe/Madrid'],
        ])->each(fn (array $city) => City::query()->updateOrCreate(['slug' => $city['slug']], $city));

        collect([
            ['name' => 'Food', 'slug' => 'food', 'type' => 'niche'],
            ['name' => 'Wellness', 'slug' => 'wellness', 'type' => 'niche'],
            ['name' => 'Beauty', 'slug' => 'beauty', 'type' => 'niche'],
            ['name' => 'Fitness', 'slug' => 'fitness', 'type' => 'niche'],
            ['name' => 'Lifestyle', 'slug' => 'lifestyle', 'type' => 'niche'],
            ['name' => 'UGC', 'slug' => 'ugc', 'type' => 'content'],
        ])->each(fn (array $tag) => Tag::query()->updateOrCreate([
            'type' => $tag['type'],
            'slug' => $tag['slug'],
        ], $tag));
    }
}
