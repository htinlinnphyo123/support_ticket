<?php

namespace Database\Factories;

use App\Enums\ActiveStatus;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Organisation>
 */
class OrganisationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'description' => fake()->paragraph(),
            'status' => fake()->randomElement(ActiveStatus::cases())->value,
            'created_by' => User::factory(),
        ];
    }
}
