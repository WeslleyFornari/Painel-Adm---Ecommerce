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
        Schema::create('extrato_splits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_ordem');
            $table->unsignedBigInteger('id_franquia')->nullable();
            $table->integer('valor_split');
            $table->decimal('percentual_split', 5, 2);
            $table->timestamps();

            $table->foreign('id_ordem')->references('id')->on('pedidos')->onDelete('cascade');
            $table->foreign('id_franquia')->references('id')->on('franquias')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('extrato_splits');
    }
};
