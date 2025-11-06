<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EntregaPedido>
 */
class EntregaPedidoFactory extends Factory
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
            'id_itens_pedido' => fake()->numberBetween(70, 129 ),
        
            'data_entrega' => fake()->dateTimeBetween('2024-06-01', '2024-06-15'),
            'data_devolucao' => fake()->dateTimeBetween('2024-06-16', '2024-06-30'),
        ];
        
    }
}
