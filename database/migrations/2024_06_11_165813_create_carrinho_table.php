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
        Schema::create('carrinho', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->nullable();
            $table->unsignedBigInteger('id_cliente')->nullable();
            $table->unsignedBigInteger('id_produto')->nullable();
            $table->decimal('valor_unitario',10,2)->nullable();
            $table->integer('qtd')->nullable();
            $table->decimal('valor_total',10,2)->nullable();
            $table->date('data_entrega')->nullable();
            $table->date('data_devolucao')->nullable();
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
        Schema::dropIfExists('carrinho');
    }
};
