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
        Schema::create('periodos_valores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_periodo')->nullable();
            $table->unsignedBigInteger('id_produto')->nullable();
            $table->decimal('valor_periodo',10,2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periodos_valores');
    }
};
