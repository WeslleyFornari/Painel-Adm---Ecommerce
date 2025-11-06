<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('feed_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('qtd_produtos');
            $table->timestamp('hora_inicio');
            $table->timestamp('hora_fim');
            $table->boolean('sucesso');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('feed_logs');
    }
};
