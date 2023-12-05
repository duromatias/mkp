<?php

use App\Modules\Auth\Models\Acceso;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddAccesoVehiculos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('accesos')->insert([
			'descripcion' => 'Vehículos',
			'ruta' => '/publicaciones',
			'icono' => 'vehiculos',
			'orden' => 15,
		]);

		$acceso = Acceso::where('descripcion', '=', 'Vehículos')->first();

		DB::table('accesos_roles')->insert([
			[
				'rol_id' => 1,
				'acceso_id' => $acceso->id
			],
			[
				'rol_id' => 2,
				'acceso_id' => $acceso->id
			],
			[
				'rol_id' => 3,
				'acceso_id' => $acceso->id
			],
			[
				'rol_id' => 4,
				'acceso_id' => $acceso->id
			]
		]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		$acceso = DB::table('accesos')->where(['descripcion' => 'Vehículos'])->first();

        DB::table('accesos_roles')->where(['acceso_id' => $acceso->id])->delete();
		DB::table('accesos')->where(['id' => $acceso->id])->delete();
    }
}
