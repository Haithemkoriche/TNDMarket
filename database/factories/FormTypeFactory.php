<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FormTypeFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'fields' => [
                ['name' => 'field1', 'type' => 'text', 'label' => 'Field 1'],
                ['name' => 'field2', 'type' => 'number', 'label' => 'Field 2'],
            ],
            'is_active' => true,
        ];
    }
}
