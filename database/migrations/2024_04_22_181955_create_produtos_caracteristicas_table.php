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
        Schema::create('produtos_caracteristicas', function (Blueprint $table) {
            $table->id();

            $table->integer('id_produto')->nullable();
            $table->string('titulo');
            $table->longText('descricao')->nullable();
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
        Schema::dropIfExists('produtos_caracteristicas');
    }
};
