<?php

namespace Database\Factories;

use App\Models\Creator;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Creator> */
class CreatorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->optional()->phoneNumber(),
            'bio' => fake()->optional()->paragraph(),
            'ugc_only' => fake()->boolean(),
            'accepts_barter' => fake()->boolean(80),
            'status' => fake()->randomElement(Creator::STATUSES),
            'rating' => fake()->optional()->numberBetween(1, 5),
            'joined_at' => now(),
            'last_active_at' => fake()->optional()->dateTimeBetween('-1 month'),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
