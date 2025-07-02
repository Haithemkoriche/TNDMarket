<?php

namespace Database\Seeders;

use App\Models\GuestBuyer;
use App\Models\Order;
use Illuminate\Database\Seeder;

class GuestBuyerSeeder extends Seeder
{
    public function run()
    {
        // Create guest buyers for existing orders that might need them
        $ordersNeedingGuests = Order::whereNull('buyer_id')->whereNull('guest_buyer_id')->get();

        foreach ($ordersNeedingGuests as $order) {
            $guest = GuestBuyer::create([
                'full_name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'phone' => fake()->phoneNumber(),
                'address' => fake()->address(),
                'wilaya' => fake()->randomElement([
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
                ]),
            ]);

            $order->update(['guest_buyer_id' => $guest->id]);
        }

        // Create additional guest buyers (using the factory directly)
        \Database\Factories\GuestBuyerFactory::new()->count(10)->create();
    }
}
