<?php

use App\Modules\Auth\Models\Acceso;
use Illuminate\Database\Migrations\Migration;

class FixAccesoMenuConsultas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $acceso = Acceso::getOne(['descripcion'=>'Consultas']);
        $acceso->ruta = '/usuario/consultas';
        $acceso->guardar();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
