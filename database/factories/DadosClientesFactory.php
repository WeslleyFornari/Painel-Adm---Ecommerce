<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DadosClientes>
 */
class DadosClientesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $siglasEstados = [
            'AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 
            'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 
            'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'
        ];

        return [
           
            'id_user' => User::factory(),
            'cpf' => fake(locale: 'pt_BR')->unique()->cpf(),
            'cnpj' => fake(locale: 'pt_BR')->unique()->cnpj(),
            'celular' => fake(locale: 'pt_BR')->cellphoneNumber(),
            'endereco' => fake(locale: 'pt_BR')->streetName(),
            'numero' => fake()->buildingNumber(),
            'cep' => fake(locale: 'pt_BR')->postcode(),
            'bairro' => fake(locale: 'pt_BR')->streetName(),
            'cidade' => fake(locale: 'pt_BR')->city(),
            'estado' => fake()->randomElement($siglasEstados),
            'pais' => 'Brasil',
            'status' => 'ativo',
        ];
    }
}
