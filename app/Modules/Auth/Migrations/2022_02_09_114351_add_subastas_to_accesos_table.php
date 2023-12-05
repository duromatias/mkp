<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddSubastasToAccesosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('accesos')->insert([
        	'id' => 9,
			'descripcion' => 'Subastas',
			'ruta' => '/subastas',
			'icono' => 'gavel',
			'orden' => 9
		]);

        DB::table('accesos_roles')->insert([
        	[
        		'id' => 20,
        		'acceso_id' => 9,
				'rol_id' => 1
			],
			[
				'id' => 21,
				'acceso_id' => 9,
				'rol_id' => 2,
			],
			[
				'id' => 22,
				'acceso_id' => 9,
				'rol_id' => 3
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
        DB::table('accesos_roles')->whereIn('id', [20, 21, 22])->delete();
        DB::table('accesos')->where('id','=', 9)->delete();
    }
}
