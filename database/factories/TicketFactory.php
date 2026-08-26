<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Ticket> */
class TicketFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'assigned_to' => null,
            'subject' => fake()->sentence(5),
            'description' => fake()->paragraph(),
            'priority' => fake()->randomElement(Ticket::PRIORITIES),
            'status' => 'open',
        ];
    }
}
