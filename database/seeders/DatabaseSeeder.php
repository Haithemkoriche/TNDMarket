<?php

namespace Database\Seeders;

use App\Models\Centre;
use App\Models\Form;
use App\Models\FormType;
use App\Models\Order;
use App\Models\Product;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Créer l'admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Créer 5 utilisateurs normaux
        User::factory()
            ->count(5)
            ->create(['role' => 'user']);

        // Créer 3 centres avec abonnements
        User::factory()
            ->count(3)
            ->create(['role' => 'centre'])
            ->each(function ($user) {
                $centre = Centre::create([
                    'user_id' => $user->id,
                    'name' => fake()->company(),
                    'address' => fake()->address(),
                    'phone' => fake()->phoneNumber(),
                    'description' => fake()->paragraph(),
                ]);

                Subscription::create([
                    'centre_id' => $centre->id,
                    'status' => 'active',
                    'start_date' => now(),
                    'end_date' => now()->addYear(),
                    'price' => fake()->randomFloat(2, 100, 500),
                ]);
            });

        // Créer les types de formulaires sans utiliser la factory
        $cognitive = FormType::create([
            'name' => 'Évaluation Cognitive',
            'description' => 'Formulaire d\'évaluation cognitive',
            'fields' => [
                ['name' => 'score_attention', 'type' => 'number', 'label' => 'Score d\'attention'],
                ['name' => 'score_memoire', 'type' => 'number', 'label' => 'Score de mémoire'],
                ['name' => 'observations', 'type' => 'textarea', 'label' => 'Observations']
            ],
            'is_active' => true,
        ]);

        $motor = FormType::create([
            'name' => 'Évaluation Motrice',
            'description' => 'Formulaire d\'évaluation motrice',
            'fields' => [
                ['name' => 'motricite_fine', 'type' => 'number', 'label' => 'Motricité fine'],
                ['name' => 'motricite_globale', 'type' => 'number', 'label' => 'Motricité globale'],
                ['name' => 'equilibre', 'type' => 'number', 'label' => 'Équilibre'],
                ['name' => 'commentaires', 'type' => 'textarea', 'label' => 'Commentaires']
            ],
            'is_active' => true,
        ]);

        // Créer des formulaires pour chaque centre
        Centre::all()->each(function ($centre) use ($cognitive, $motor) {
            Form::factory()
                ->count(5)
                ->create([
                    'centre_id' => $centre->id,
                    'form_type_id' => fake()->randomElement([$cognitive->id, $motor->id]),
                    'form_data' => function () use ($cognitive, $motor) {
                        $type = fake()->randomElement([$cognitive, $motor]);
                        $data = [];

                        foreach ($type->fields as $field) {
                            $data[$field['name']] = $field['type'] === 'number'
                                ? fake()->numberBetween(1, 10)
                                : fake()->sentence();
                        }

                        return $data;
                    }
                ]);
        });

        // Créer des produits pour les utilisateurs normaux
        User::where('role', 'user')->get()->each(function ($user) {
            Product::factory()
                ->count(3)
                ->create(['user_id' => $user->id]);
        });

        // Créer des commandes
        Product::where('stock', '>', 0)->get()->each(function ($product) {
            Order::factory()
                ->count(2)
                ->create([
                    'product_id' => $product->id,
                    'total_price' => function ($attributes) use ($product) {
                        return $product->price * $attributes['quantity'];
                    }
                ]);

            // Mettre à jour le stock
            $product->refresh();
            $product->stock -= 2;
            $product->save();
        });
    }
}
