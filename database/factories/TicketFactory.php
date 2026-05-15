<?php

namespace Database\Factories;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ticket>
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
            'status' => TicketStatus::Open->value,
            'file_type' => null,
            'link' => null,
            'creator_id' => User::factory(),
            'priority' => fake()->randomElement(TicketPriority::cases())->value,
            'agent_id' => User::factory(),
        ];
    }
}
