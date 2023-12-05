<?php

use App\Modules\Auth\Models\Acceso;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ActualizarAccesoSegurosTablaAccesos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $acceso = Acceso::getById(14);
        $acceso->icono = 'verified_user';
        $acceso->orden = 45;
        $acceso->guardar();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $acceso = Acceso::getById(14);
        $acceso->icono = null;
        $acceso->orden = 90;
        $acceso->guardar();
    }
}
