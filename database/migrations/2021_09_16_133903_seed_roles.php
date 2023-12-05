<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SeedRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		DB::table('roles')->insert([
			[
				'id' => 1,
				'descripcion' => 'Administrador'
			],
			[
				'id' => 2,
				'descripcion' => 'Usuario Agencia'
			],
			[
				'id' => 3,
				'descripcion' => 'Usuario Particular'
			],
			[
				'id' => 4,
				'descripcion' => 'Usuario sin login'
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
		DB::table('accesos')->whereIn('id', [1,2,3,4])->delete();
	}
}
