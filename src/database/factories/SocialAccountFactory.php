<?php

namespace Database\Factories;

use App\Models\Creator;
use App\Models\SocialAccount;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<SocialAccount> */
class SocialAccountFactory extends Factory
{
    public function definition(): array
    {
        $handle = fake()->unique()->userName();

        return [
            'accountable_type' => Creator::class,
            'accountable_id' => Creator::factory(),
            'platform' => fake()->randomElement(['instagram', 'tiktok', 'youtube']),
            'handle' => $handle,
            'url' => 'https://example.com/'.$handle,
            'is_primary' => false,
        ];
    }
}
