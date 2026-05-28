<?php

namespace Database\Factories;

use App\Models\Prospect;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Prospect> */
class ProspectFactory extends Factory
{
    public function definition(): array
    {
        $handle = fake()->unique()->userName();

        return [
            'prospect_type' => Prospect::TYPE_CREATOR,
            'platform' => fake()->randomElement(['instagram', 'tiktok', 'youtube']),
            'handle' => $handle,
            'profile_url' => 'https://example.com/'.$handle,
            'name' => fake()->name(),
            'city_name' => fake()->city(),
            'country_name' => fake()->country(),
            'category' => fake()->randomElement(['food', 'wellness', 'beauty', 'fitness']),
            'status' => fake()->randomElement(Prospect::STATUSES),
            'contacted_at' => fake()->optional()->dateTimeBetween('-1 month'),
            'responded_at' => fake()->optional()->dateTimeBetween('-1 month'),
            'rejection_reason' => null,
            'notes' => fake()->optional()->sentence(),
            'source' => fake()->randomElement(['manual', 'instagram_search', 'referral']),
        ];
    }

    public function creator(): static
    {
        return $this->state(fn (): array => ['prospect_type' => Prospect::TYPE_CREATOR]);
    }

    public function brand(): static
    {
        return $this->state(fn (): array => [
            'prospect_type' => Prospect::TYPE_BRAND,
            'name' => fake()->company(),
            'category' => fake()->randomElement(['restaurant', 'hotel', 'beauty', 'wellness']),
        ]);
    }
}
