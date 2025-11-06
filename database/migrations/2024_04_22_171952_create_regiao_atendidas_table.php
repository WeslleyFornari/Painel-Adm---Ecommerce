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
        Schema::create('regiao_atendidas', function (Blueprint $table) {
            $table->id();

            $table->integer('id_franqueado');
            $table->string('cidade')->nullable();
            $table->string('bairro')->nullable();

            $table->decimal('valor_entrega',10,2); 
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
        Schema::dropIfExists('regiao_atendidas');
    }
};
