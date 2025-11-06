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
        Schema::create('franquias', function (Blueprint $table) {

            $table->id();
            $table->string('nome_responsavel');
            $table->string('nome_franquia');
            $table->string('cpf')->nullable();
            $table->string('cnpj')->nullable();
            $table->string('celular')->nullable();
            $table->string('telefone')->nullable();
            $table->string('cep')->nullable();
            $table->string('endereco')->nullable();
            $table->integer('numero')->nullable();
            $table->string('complemento')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cidade')->nullable();
            $table->string('estado')->nullable();
            $table->string('pais')->nullable();
           
            $table->enum('tipo_franqueado',['toy','trip'])->default('trip');
            $table->enum('status',['ativo','inativo'])->default('ativo');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('franquias');
    }
};
