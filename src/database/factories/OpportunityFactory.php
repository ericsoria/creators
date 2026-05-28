<?php

namespace Database\Factories;

use App\Models\Campaign;
use App\Models\Creator;
use App\Models\Opportunity;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Opportunity> */
class OpportunityFactory extends Factory
{
    public function definition(): array
    {
        return [
            'campaign_id' => Campaign::factory(),
            'creator_id' => Creator::factory(),
            'status' => fake()->randomElement(Opportunity::STATUSES),
            'channel' => fake()->randomElement(['instagram_dm', 'email', 'tiktok_dm', 'whatsapp']),
            'source_account' => fake()->optional()->userName(),
            'message_template' => fake()->optional()->paragraph(),
            'first_contacted_at' => fake()->optional()->dateTimeBetween('-2 weeks'),
            'last_contacted_at' => fake()->optional()->dateTimeBetween('-1 week'),
            'responded_at' => fake()->optional()->dateTimeBetween('-5 days'),
            'follow_up_count' => fake()->numberBetween(0, 3),
            'rejection_reason' => fake()->optional()->sentence(),
            'notes' => fake()->optional()->sentence(),
            'assigned_to' => User::factory(),
            'converted_to_collaboration_id' => null,
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Opportunity::STATUS_CONTACTED,
        ]);
    }

    public function terminal(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Opportunity::STATUS_REJECTED,
        ]);
    }
}
