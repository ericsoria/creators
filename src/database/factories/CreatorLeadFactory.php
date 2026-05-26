<?php

namespace Database\Factories;

use App\Models\CreatorLead;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<CreatorLead> */
class CreatorLeadFactory extends Factory
{
    public function definition(): array
    {
        $handle = fake()->unique()->userName();

        return [
            'platform' => fake()->randomElement(['instagram', 'tiktok', 'youtube']),
            'handle' => $handle,
            'profile_url' => 'https://example.com/'.$handle,
            'name' => fake()->name(),
            'city_name' => fake()->city(),
            'country_name' => fake()->country(),
            'niche' => fake()->randomElement(['food', 'wellness', 'beauty', 'fitness']),
            'status' => fake()->randomElement(CreatorLead::STATUSES),
            'contacted_at' => fake()->optional()->dateTimeBetween('-1 month'),
            'responded_at' => fake()->optional()->dateTimeBetween('-1 month'),
            'rejection_reason' => null,
            'notes' => fake()->optional()->sentence(),
            'source' => fake()->randomElement(['manual', 'instagram_search', 'referral']),
        ];
    }
}
