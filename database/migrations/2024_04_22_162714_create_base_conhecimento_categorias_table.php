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
        Schema::create('base_conhecimento_categorias', function (Blueprint $table) {
            
            $table->id();
                 
            $table->string('titulo')->nullable();
            $table->enum('tipo',['toy','trip'])->default('trip');
            $table->enum('status',['ativo','inativo']);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('base_conhecimento_categorias');
    }
};
