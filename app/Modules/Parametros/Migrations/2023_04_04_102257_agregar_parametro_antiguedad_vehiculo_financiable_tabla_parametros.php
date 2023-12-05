<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AgregarParametroAntiguedadVehiculoFinanciableTablaParametros extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('parametros')->insert(
            [
                'id' => 14,
                'descripcion' => 'Cantidad de años de antiguedad que puede tener un vehículo para que el mismo sea fianciable ',
                'valor' => 15
            ],
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('parametros')->where('id', '=', 14)->delete();
    }
}
