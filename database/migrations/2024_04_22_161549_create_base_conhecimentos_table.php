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
        Schema::create('base_conhecimentos', function (Blueprint $table) {
            $table->id();
           
            $table->integer('id_categoria');
            $table->string('titulo')->nullable();
            $table->longText('descricao')->nullable();                                         
            $table->enum('tipo',['toy','trip']);
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
        Schema::dropIfExists('base_conhecimentos');
    }
};
