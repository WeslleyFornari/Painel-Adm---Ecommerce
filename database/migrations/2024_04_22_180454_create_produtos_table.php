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
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();

            $table->string('nome');
            $table->string('slug'); 
            $table->integer('id_categoria');
            $table->longText('descricao');
            $table->string('marca');
            $table->decimal('valor_base_diaria',10,2)->nullable();
            $table->string('tempo_entrega')->nullable();
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
        Schema::dropIfExists('produtos');
    }
};
