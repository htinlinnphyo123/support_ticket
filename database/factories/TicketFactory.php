<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'description' => fake()->paragraphs(3, true),
            'status' => \App\Enums\TicketStatus::Open->value,
            'file_type' => null,
            'link' => null,
            'creator_id' => \App\Models\User::factory(),
            'priority' => fake()->randomElement(\App\Enums\TicketPriority::cases())->value,
            'agent_id' => \App\Models\User::factory(),
        ];
    }
}
