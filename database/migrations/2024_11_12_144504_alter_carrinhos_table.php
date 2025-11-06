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
        Schema::table('carrinhos', function (Blueprint $table) {
            $table->enum('tipo',['toy','trip'])->default('trip')->after('data_devolucao');
            $table->enum('modalidade',['alugar','comprar'])->default('alugar')->after('data_devolucao');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carrinhos', function (Blueprint $table) {
            $table->dropColumn('modalidade');
        });
    }
};
