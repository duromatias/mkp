<?php

use App\Modules\Auth\Models\Acceso;
use App\Modules\Auth\Models\AccesosRoles;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddConfiguracionSubaccesos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		$acceso = Acceso::agregar('Banner', 'configuracion/banner', 'settings', 1, 'Configuración');
		AccesosRoles::agregar(1, $acceso->id);

		$acceso = Acceso::agregar('Parámetros', 'configuracion/parametros', 'settings', 2, 'Configuración');
		AccesosRoles::agregar(1, $acceso->id);

		$acceso  = Acceso::agregar('Términos y Condiciones', 'configuracion/terminos-y-condiciones', 'settings', 3, 'Configuración');
		AccesosRoles::agregar(1, $acceso->id);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $acceso = Acceso::where('descripcion', 'Banner')->first();
		DB::table('accesos_roles')->where('acceso_id', $acceso->id)->delete();
		DB::table('accesos')->where('id', $acceso->id)->delete();

		$acceso = Acceso::where('descripcion', 'Parámetros')->first();
		DB::table('accesos_roles')->where('acceso_id', $acceso->id)->delete();
		DB::table('accesos')->where('id', $acceso->id)->delete();

		$acceso = Acceso::where('descripcion', 'Términos y Condiciones')->first();
		DB::table('accesos_roles')->where('acceso_id', $acceso->id)->delete();
		DB::table('accesos')->where('id', $acceso->id)->delete();
    }
}
