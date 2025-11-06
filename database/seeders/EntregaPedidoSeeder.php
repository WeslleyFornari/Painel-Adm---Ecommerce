<?php

namespace Database\Seeders;

use App\Models\EntregaPedido;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EntregaPedidoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EntregaPedido::factory(60)->create();
    }
}
