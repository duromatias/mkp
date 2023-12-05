<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CrearParametroTelefonoSeguros extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('parametros')->insert([
            //se agrega con el mismo valor que 'Teléfono contacto plataforma' porque no se tiene todavía el nro que va
            'id' => 13,
            'descripcion' => 'Teléfono de contacto con Seguro Mercado Abierto',
            'valor' => '+5493415324679',
            'nombre' => 'segurosTelefono'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
