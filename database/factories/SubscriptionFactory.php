<?php

namespace Database\Factories;

use App\Models\Centre;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionFactory extends Factory
{
    public function definition()
    {
        return [
            'centre_id' => Centre::factory(),
            'status' => $this->faker->randomElement(['active', 'suspended', 'cancelled']),
            'start_date' => now(),
            'end_date' => now()->addYear(),
            'price' => $this->faker->randomFloat(2, 100, 500),
        ];
    }
}
