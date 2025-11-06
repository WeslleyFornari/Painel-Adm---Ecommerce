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
    Schema::create('itens_pedido', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('id_pedido');
        $table->unsignedBigInteger('id_produto');
        $table->integer('qtd');
        $table->decimal('valor_unitario', 10, 2);
        $table->decimal('valor_total', 10, 2);
        $table->unsignedBigInteger('id_entrega_pedido');
        $table->enum('status', ['ativo', 'inativo'])->default('ativo');
        $table->timestamps();
        $table->softDeletes();
        
        $table->foreign('id_pedido')->references('id')->on('pedidos')->onUpdate('cascade')->onDelete('cascade');
        $table->foreign('id_produto')->references('id')->on('produtos')->onUpdate('cascade')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itens_pedido');
    }
};
