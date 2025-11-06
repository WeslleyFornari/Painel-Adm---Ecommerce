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
        Schema::create('pedidos', function (Blueprint $table) {

            $table->id();

            $table->integer('numero');
            $table->unsignedBigInteger('id_franquia');
            $table->integer('id_cliente');
            $table->decimal('valor_total',10,2);
            $table->decimal('valor_desconto',10,2)->nullable();
            $table->string('id_cupom')->nullable();
            $table->string('observacoes')->nullable();
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
        Schema::dropIfExists('pedidos');
    }
};
