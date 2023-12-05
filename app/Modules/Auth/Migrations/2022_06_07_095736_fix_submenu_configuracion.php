<?php
use App\Modules\Auth\Models\Acceso;
use Illuminate\Database\Migrations\Migration;

class FixSubmenuConfiguracion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		$acceso = Acceso::getOne(['descripcion' => 'Banner']);
        $acceso->ruta =  '/configuracion/banner';
        $acceso->guardar();

		$acceso = Acceso::getOne(['descripcion' => 'Parámetros']);
        $acceso->ruta = '/configuracion/parametros';
        $acceso->guardar();

		$acceso = Acceso::getOne(['descripcion' => 'Términos y Condiciones']);
        $acceso->ruta = '/configuracion/terminos-y-condiciones';
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
