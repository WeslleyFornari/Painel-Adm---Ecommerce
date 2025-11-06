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
        Schema::table('produtos', function (Blueprint $table) {
            $table->unsignedBigInteger('id_franquia')->nullable()->after('id_categoria');
            $table->enum('modalidade',['alugar','vender', 'alugar_vender'])->default('alugar')->after('orientacoes');
            $table->enum('tipo',['trip','toy'])->default('trip')->after('orientacoes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produtos', function (Blueprint $table) {
            $table->dropColumn('id_franquia');
            $table->dropColumn('modalidade');
            $table->dropColumn('tipo');
        });
    }
};
