<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddVariavelToTipoLocacaoEnum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        
        $this->updateEnumInTable('estoque');
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->removeEnumValueFromTable('estoque');
        
    }
    
    /**
     * Atualiza o enum tipo_locacao em uma tabela específica
     *
     * @param string $tableName
     * @return void
     */
    private function updateEnumInTable($tableName)
    {
        if (Schema::hasColumn($tableName, 'tipo_locacao')) {
            $enumValues = DB::select("SHOW COLUMNS FROM {$tableName} WHERE Field = 'tipo_locacao'")[0]->Type;
            
            preg_match('/^enum\((.*)\)$/', $enumValues, $matches);
            $currentValues = str_getcsv($matches[1], ',', "'");
            
            if (!in_array('variavel', $currentValues)) {
                $currentValues[] = 'variavel';
            }
            
            $newValues = "'" . implode("','", $currentValues) . "'";
            
            DB::statement("ALTER TABLE {$tableName} MODIFY COLUMN tipo_locacao ENUM($newValues)");
        }
    }
    
    /**
     * Remove o valor 'variavel' do enum tipo_locacao em uma tabela específica
     *
     * @param string $tableName
     * @return void
     */
    private function removeEnumValueFromTable($tableName)
    {
        if (Schema::hasColumn($tableName, 'tipo_locacao')) {
            $enumValues = DB::select("SHOW COLUMNS FROM {$tableName} WHERE Field = 'tipo_locacao'")[0]->Type;
            
            preg_match('/^enum\((.*)\)$/', $enumValues, $matches);
            $currentValues = str_getcsv($matches[1], ',', "'");
            
            $currentValues = array_filter($currentValues, function($value) {
                return $value !== 'variavel';
            });
            
            $newValues = "'" . implode("','", $currentValues) . "'";
            
            DB::statement("ALTER TABLE {$tableName} MODIFY COLUMN tipo_locacao ENUM($newValues)");
        }
    }
}
