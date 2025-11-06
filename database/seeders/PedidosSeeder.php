<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PedidosSeeder extends Seeder
{
    public function run()
    {
        $pedidos = DB::table('pedidos')->get();

        foreach ($pedidos as $pedido) {
            $token = Str::uuid()->toString();

            if (!$pedido->token){
                DB::table('pedidos')->where('id', $pedido->id)->update(['token' => $token]);
            }
                
        }
    }
}
