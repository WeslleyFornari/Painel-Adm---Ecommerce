<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('itens_pedido', function (Blueprint $table) {
            $table->enum('tipo_locacao',['aluguel','venda'])->default('aluguel')->after('valor_unitario');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('itens_pedido', function (Blueprint $table) {
            $table->dropColumn('tipo_locacao');
        });
    }
};
