<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => 1,
            'name' => fake()->name(),
            //'description' => fake()->lorem(),
            'venue_id' => 0,
            'start' => fake()->dateTimeThisMonth(),
            'end' => fake()->dateTimeThisMonth(),
        ];
    }
}
