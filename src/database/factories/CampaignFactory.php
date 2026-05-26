<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Campaign;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Campaign>
 */
class CampaignFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startsAt = fake()->dateTimeBetween('-1 week', '+1 month');

        return [
            'brand_id' => Brand::factory(),
            'name' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'objective' => fake()->sentence(),
            'status' => fake()->randomElement(Campaign::STATUSES),
            'starts_at' => $startsAt,
            'ends_at' => fake()->dateTimeBetween($startsAt, '+2 months'),
            'compensation_type' => fake()->randomElement(['barter', 'paid', 'gift']),
            'requirements' => fake()->paragraph(),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
