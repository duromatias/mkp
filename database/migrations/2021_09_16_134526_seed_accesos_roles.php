<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SeedAccesosRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		DB::table('accesos_roles')->insert([
			[
				'id' => 1,
				'rol_id' => 1,
				'acceso_id' => 5
			],
			[
				'id' => 2,
				'rol_id' => 1,
				'acceso_id' => 7
			],
			[
				'id' => 3,
				'rol_id' => 1,
				'acceso_id' => 8
			],
			[
				'id' => 4,
				'rol_id' => 1,
				'acceso_id' => 11
			],
			[
				'id' => 5,
				'rol_id' => 2,
				'acceso_id' => 1
			],
			[
				'id' => 6,
				'rol_id' => 2,
				'acceso_id' => 2
			],
			[
				'id' => 7,
				'rol_id' => 2,
				'acceso_id' => 3
			],
			[
				'id' => 8,
				'rol_id' => 2,
				'acceso_id' => 4
			],
			[
				'id' => 9,
				'rol_id' => 2,
				'acceso_id' => 5
			],
			[
				'id' => 10,
				'rol_id' => 2,
				'acceso_id' => 9
			],
			[
				'id' => 11,
				'rol_id' => 2,
				'acceso_id' => 10
			],
			[
				'id' => 12,
				'rol_id' => 3,
				'acceso_id' => 1
			],
			[
				'id' => 13,
				'rol_id' => 3,
				'acceso_id' => 2
			],
			[
				'id' => 14,
				'rol_id' => 3,
				'acceso_id' => 3
			],
			[
				'id' => 15,
				'rol_id' => 3,
				'acceso_id' => 4
			],
			[
				'id' => 16,
				'rol_id' => 3,
				'acceso_id' => 5
			],
			[
				'id' => 17,
				'rol_id' => 3,
				'acceso_id' => 6
			],
			[
				'id' => 18,
				'rol_id' => 3,
				'acceso_id' => 9
			],
			[
				'id' => 19,
				'rol_id' => 3,
				'acceso_id' => 10
			],
			[
				'id' => 20,
				'rol_id' => 4,
				'acceso_id' => 1
			],
			[
				'id' => 21,
				'rol_id' => 4,
				'acceso_id' => 2
			],
			[
				'id' => 22,
				'rol_id' => 4,
				'acceso_id' => 3
			],
			[
				'id' => 23,
				'rol_id' => 4,
				'acceso_id' => 4
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
		DB::table('accesos_roles')->whereIn('id', [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23])->delete();

	}
}
