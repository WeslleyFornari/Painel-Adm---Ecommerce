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
        Schema::create('dados_clientes', function (Blueprint $table) {
            $table->id();       

            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->string('cpf')->nullable();
            $table->string('cnpj')->nullable();
            $table->date('data_nascimento')->nullable();
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
            $table->enum('status',['ativo','inativo','removido'])->default('ativo');

            $table->timestamps();
            $table->softDeletes();
        }); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dados_clientes');
    }
};
