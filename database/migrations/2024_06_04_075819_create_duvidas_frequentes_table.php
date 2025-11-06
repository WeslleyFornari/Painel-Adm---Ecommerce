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
        Schema::create('duvidas_frequentes', function (Blueprint $table) {
            $table->id();

            $table->string('pergunta');
            $table->longText('resposta');
            $table->enum('tipo_franqueado',['toy','trip'])->default('trip');
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
        Schema::dropIfExists('duvidas_frequentes');
    }
};
