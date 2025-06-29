<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 10, 100),
            'photo' => null,
            'stock' => $this->faker->numberBetween(0, 50),
            'is_active' => true,
        ];
    }
}
