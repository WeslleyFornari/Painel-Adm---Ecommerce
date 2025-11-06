<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ItensPedidos>
 */
class ItensPedidosFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        
          'id_pedido' => fake()->numberBetween(116, 175 ),
          'id_produto' => fake()->numberBetween(12, 51 ),
          'qtd' => '1',
          'valor_unitario' => fake()->numberBetween(50, 200 ),
          'valor_total' => fake()-> numberBetween(200, 450 ),
          'id_entrega_pedido' => fake()-> numberBetween(57, 116 ),
          
        ];
    }
}
