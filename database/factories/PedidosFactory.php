<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pedidos>
 */
class PedidosFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            
          //  'numero' => Str::random(6),
            'numero' => sprintf('%02d-%04d', rand(0, 99), rand(0, 9999)),
            'id_franquia' => 1,
            'id_cliente' => fake()->numberBetween(17, 46 ),
         //   'id_cliente' => User::factory()->numberBetween(17, 46 ),
            'valor_frete' => fake()->randomElement([9.00, 19.00, 29.00, 39.00]),
            'tipo_frete' => $this->faker->randomElement(['expresso', 'economico']),
            'id_forma_pagamento' => fake()->randomElement([1, 2]),
            'id_status' => fake()->randomElement([1, 2, 3, 4, 5, 6, 7]),
            'valor_total' => fake()->numberBetween(79, 349 ),
            'created_at' => fake()->dateTimeBetween('2024-02-01', '2024-06-30'),
        ];
    }
}
