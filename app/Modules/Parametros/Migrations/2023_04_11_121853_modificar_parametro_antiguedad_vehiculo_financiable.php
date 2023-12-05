<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ModificarParametroAntiguedadVehiculoFinanciable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('parametros')->where('id', '=', 14)->update(['descripcion' => 'Cantidad de años de antigüedad que puede tener un vehículo para que el mismo sea financiable']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('parametros')->where('id', '=', 14)->update(['descripcion' => 'Cantidad de años de antiguedad que puede tener un vehículo para que el mismo sea fianciable']);
    }
}
