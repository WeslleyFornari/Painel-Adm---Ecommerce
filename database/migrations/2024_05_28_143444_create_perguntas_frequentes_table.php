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
        Schema::create('perguntas_frequentes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_produto');
            $table->unsignedBigInteger('id_cliente');
            $table->string('pergunta')->nullable();
            $table->string('resposta')->nullable();
            $table->enum('status',['ativo','inativo']);
            
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_produto')->references('id')->on('produtos')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perguntas_frequentes');
    }
};
