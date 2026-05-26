<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Creator;
use App\Models\CreatorLead;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class DemoCreatorNetworkSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment('local')) {
            return;
        }

        $lead = CreatorLead::query()->firstOrCreate(
            ['platform' => 'instagram', 'handle' => 'demo_creator_lead'],
            [
                'profile_url' => 'https://instagram.com/demo_creator_lead',
                'name' => 'Demo Creator Lead',
                'city_name' => 'Barcelona',
                'country_name' => 'Spain',
                'niche' => 'food',
                'status' => CreatorLead::STATUS_DISCOVERED,
                'source' => 'manual',
            ],
        );

        $creator = Creator::query()->firstOrCreate(
            ['username' => 'demo_creator'],
            [
                'name' => 'Demo Creator',
                'email' => 'demo.creator@example.com',
                'ugc_only' => false,
                'accepts_barter' => true,
                'status' => Creator::STATUS_ACTIVE,
                'joined_at' => now(),
                'notes' => $lead->notes,
            ],
        );

        $creator->cities()->syncWithoutDetaching(City::query()->where('slug', 'barcelona')->pluck('id'));
        $creator->tags()->syncWithoutDetaching(Tag::query()->whereIn('slug', ['food', 'lifestyle'])->pluck('id'));
        $creator->socialAccounts()->firstOrCreate(
            ['platform' => 'instagram', 'handle' => 'demo_creator'],
            ['url' => 'https://instagram.com/demo_creator', 'is_primary' => true],
        );
    }
}
