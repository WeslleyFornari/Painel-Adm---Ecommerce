<?php

namespace Database\Seeders;

use App\Models\ItensPedidos;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItensPedidosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ItensPedidos::factory(60)->create();
    }
}
