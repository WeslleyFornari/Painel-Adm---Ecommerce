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
        Schema::table('produto_categorias', function (Blueprint $table) {
            $table->enum('tipo',['trip','toy'])->default('trip')->after('descricao');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produto_categorias', function (Blueprint $table) {
            $table->dropColumn('tipo');
        });
    }
};
