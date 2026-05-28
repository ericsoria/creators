<?php

namespace Database\Factories;

use App\Models\Opportunity;
use App\Models\OpportunityEvent;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<OpportunityEvent> */
class OpportunityEventFactory extends Factory
{
    public function definition(): array
    {
        return [
            'opportunity_id' => Opportunity::factory(),
            'type' => fake()->randomElement(OpportunityEvent::TYPES),
            'old_status' => fake()->optional()->randomElement(Opportunity::STATUSES),
            'new_status' => fake()->optional()->randomElement(Opportunity::STATUSES),
            'message' => fake()->optional()->paragraph(),
            'metadata' => ['source' => 'factory'],
            'created_by' => User::factory(),
        ];
    }
}
