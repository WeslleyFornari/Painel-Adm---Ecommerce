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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();

            $table->string('titulo');

            $table->integer('ordem')->nullable();
            $table->integer('id_media_desktop')->nullable();
            $table->integer('id_media_mobile')->nullable();

            $table->string('url');
            $table->enum('new_window',['sim','nao'])->default('nao');
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
        Schema::dropIfExists('banners');
    }
};
