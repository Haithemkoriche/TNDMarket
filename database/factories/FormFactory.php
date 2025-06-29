<?php

namespace Database\Factories;

use App\Models\Centre;
use App\Models\FormType;
use Illuminate\Database\Eloquent\Factories\Factory;

class FormFactory extends Factory
{
    public function definition()
    {
        return [
            'centre_id' => Centre::factory(),
            'form_type_id' => FormType::factory(),
            'patient_name' => $this->faker->firstName(),
            'patient_surname' => $this->faker->lastName(),
            'form_data' => [],
        ];
    }
}
