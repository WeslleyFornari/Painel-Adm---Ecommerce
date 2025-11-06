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
        Schema::table('estoques', function (Blueprint $table) {
            $table->enum('tipo_locacao',['aluguel','aluguel_venda','venda'])->default('aluguel')->after('codigo');
            $table->decimal('valor_compra',10,2)->nullable();            
            $table->date('data_compra')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('estoques', function (Blueprint $table) {
            $table->dropColumn('tipo_locacao');
        });
    }
};
