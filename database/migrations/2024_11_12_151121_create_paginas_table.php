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
        Schema::create('paginas', function (Blueprint $table) {
            $table->id();
            $table->string('icone')->nullable();
            $table->string('titulo')->nullable();
            $table->string('slug')->nullable();
            $table->enum('visualizar',['sim','nao'])->nullable();
            $table->enum('criar',['sim','nao'])->nullable();
            $table->enum('editar',['sim','nao'])->nullable();
            $table->enum('deletar',['sim','nao'])->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paginas');
    }
};
