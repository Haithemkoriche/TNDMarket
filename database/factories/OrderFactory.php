<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition()
    {
        return [
            'product_id' => Product::factory(),
            'buyer_id' => User::factory(),
            'quantity' => $this->faker->numberBetween(1, 5),
            'total_price' => function (array $attributes) {
                $product = Product::find($attributes['product_id']);
                return $product->price * $attributes['quantity'];
            },
            'status' => $this->faker->randomElement(['en_attente', 'confirme', 'livre']),
        ];
    }
}
