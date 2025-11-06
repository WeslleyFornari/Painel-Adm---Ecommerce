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
        Schema::create('cupons', function (Blueprint $table) {
            $table->id();
           
            $table->string('codigo');
            $table->enum('tipo',['porcentagem','real']);
            $table->enum('modalidade',['frete','produtos']);
            $table->integer('qtd');
            $table->decimal('valor',10,2);
            $table->decimal('valor_minimo',10,2);
           
            $table->enum('status',['ativo','inativo'])->default('inativo');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cupons');
    }
};
