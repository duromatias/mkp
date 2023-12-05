<?php

use App\Modules\Auth\Models\Acceso;
use Illuminate\Database\Migrations\Migration;

class FixRutaEditarTyc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $acceso = Acceso::getById(7);
        $acceso->ruta = '/admin/terminos-y-condiciones';
        $acceso->guardar();
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
