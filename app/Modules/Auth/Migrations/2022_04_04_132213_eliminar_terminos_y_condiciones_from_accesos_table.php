<?php

use App\Modules\Users\Models\Rol;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class EliminarTerminosYCondicionesFromAccesosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	DB::table('accesos_roles')
			->where('acceso_id', '=', 7)
			->where('rol_id', '=', Rol::ADMINISTRADOR)
			->delete();

    	DB::table('accesos')->where('id', 7)->delete();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('accesos')->insert([
        	'id' => 7,
        	'descripcion' => 'Términos y Condiciones',
			'ruta' => '/admin/terminos-y-condiciones',
			'icono' => 'format_list_numbered',
			'orden' => 7
		]);

		DB::table('accesos_roles')->insert([
			'acceso_id' => 7,
			'rol_id' => Rol::ADMINISTRADOR
		]);
	}
}
