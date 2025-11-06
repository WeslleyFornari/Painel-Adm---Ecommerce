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
        Schema::table('franquias', function (Blueprint $table) {
            $table->string('apelido')->nullable()->after('nome_franquia')->comment('Apelido da franquia para exibição');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('franquias', function (Blueprint $table) {
            $table->dropColumn('apelido');
        });
    }
};
