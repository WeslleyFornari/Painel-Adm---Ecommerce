<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddVariavelToModalidadeEnumInProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $enumValues = DB::select("SHOW COLUMNS FROM produtos WHERE Field = 'modalidade'")[0]->Type;
        
        preg_match('/^enum\((.*)\)$/', $enumValues, $matches);
        $currentValues = str_getcsv($matches[1], ',', "'");
        
        if (!in_array('variavel', $currentValues)) {
            $currentValues[] = 'variavel';
        }
        
        $newValues = "'" . implode("','", $currentValues) . "'";
        
        DB::statement("ALTER TABLE produtos MODIFY COLUMN modalidade ENUM($newValues) NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $enumValues = DB::select("SHOW COLUMNS FROM produtos WHERE Field = 'modalidade'")[0]->Type;
        
        preg_match('/^enum\((.*)\)$/', $enumValues, $matches);
        $currentValues = str_getcsv($matches[1], ',', "'");
        
        $currentValues = array_filter($currentValues, function($value) {
            return $value !== 'variavel';
        });
        
        $newValues = "'" . implode("','", $currentValues) . "'";
        
        DB::statement("ALTER TABLE produtos MODIFY COLUMN modalidade ENUM($newValues) NOT NULL");
    }
}
