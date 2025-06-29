<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CentreFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->company(),
            'address' => $this->faker->address(),
            'phone' => $this->faker->phoneNumber(),
            'description' => $this->faker->paragraph(),
        ];
    }
}
