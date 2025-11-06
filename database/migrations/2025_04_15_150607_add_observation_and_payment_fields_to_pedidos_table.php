<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddObservationAndPaymentFieldsToPedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->text('observacoes_internas')->nullable()->after('id_status');
            $table->text('observacoes_cliente')->nullable()->after('observacoes_internas');
            $table->decimal('pagamento_parcial_inicial', 10, 2)->nullable()->after('observacoes_cliente');
            $table->decimal('pagamento_parcial_final', 10, 2)->nullable()->after('pagamento_parcial_inicial');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn('observacoes_internas');
            $table->dropColumn('observacoes_cliente');
            $table->dropColumn('pagamento_parcial_inicial');
            $table->dropColumn('pagamento_parcial_final');
        });
    }
}
