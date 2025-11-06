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
            $table->string('chave_secreta_inter')->nullable()->after('apelido');
            $table->string('chave_publica_inter')->nullable()->after('chave_secreta_inter');
            $table->string('chave_pix_inter')->nullable()->after('chave_publica_inter');
            $table->string('certificado_inter')->nullable()->after('chave_pix_inter')->comment('caminho para arquivo .crt');
            $table->string('chave_inter')->nullable()->after('certificado_inter')->comment('caminho para arquivo .key');
            $table->string('webhook_inter')->nullable()->after('chave_inter')->comment('caminho para arquivo .ca');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('franquias', function (Blueprint $table) {
            $table->dropColumn([
                'chave_secreta_inter',
                'chave_publica_inter',
                'chave_pix_inter',
                'certificado_inter',
                'chave_inter',
                'webhook_inter'
            ]);
        });
    }
};
