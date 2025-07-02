<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GuestBuyerFactory extends Factory
{
    public function definition()
    {
        $wilayas = [
            'Adrar',
            'Chlef',
            'Laghouat',
            'Oum El Bouaghi',
            'Batna',
            'Béjaïa',
            'Biskra',
            'Béchar',
            'Blida',
            'Bouira',
            'Tamanrasset',
            'Tébessa',
            'Tlemcen',
            'Tiaret',
            'Tizi Ouzou',
            'Alger',
            'Djelfa',
            'Jijel',
            'Sétif',
            'Saïda',
            'Skikda',
            'Sidi Bel Abbès',
            'Annaba',
            'Guelma',
            'Constantine',
            'Médéa',
            'Mostaganem',
            'M\'Sila',
            'Mascara',
            'Ouargla',
            'Oran',
            'El Bayadh',
            'Illizi',
            'Bordj Bou Arréridj',
            'Boumerdès',
            'El Tarf',
            'Tindouf',
            'Tissemsilt',
            'El Oued',
            'Khenchela',
            'Souk Ahras',
            'Tipaza',
            'Mila',
            'Aïn Defla',
            'Naâma',
            'Aïn Témouchent',
            'Ghardaïa',
            'Relizane'
        ];

        return [
            'full_name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'wilaya' => $this->faker->randomElement($wilayas),
        ];
    }
}
