<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UsuarioCampoDni extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('usuarios', function(Blueprint $table) {
            $table->dropColumn('tipo_doc');
            $table->renameColumn('nro_doc',  'dni' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('usuarios', function(Blueprint $table) {
            $table->renameColumn('dni',  'nro_doc' );
        });
        
        Schema::table('usuarios', function(Blueprint $table) {
			$table->string('tipo_doc');
        });
    }
}
