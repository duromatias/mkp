<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModificarNombresColumnas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subastas', function (Blueprint $table) {
            $table->renameColumn('fecha_inicio_carga', 'fecha_inicio_inscripcion');
            $table->renameColumn('fecha_fin_carga',    'fecha_fin_inscripcion'   );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subastas', function (Blueprint $table) {
            $table->renameColumn('fecha_inicio_inscripcion', 'fecha_inicio_carga');
            $table->renameColumn('fecha_fin_inscripcion',    'fecha_fin_carga'   );
        });
    }
}
