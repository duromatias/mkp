<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class RemoveAccesoRolParticularSubastas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('accesos_roles')
			->where('rol_id', 3)
			->where('acceso_id', 9)
			->delete();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('accesos_roles')->insert([
        	'rol_id' => 3,
			'acceso_id' => 9
		]);
    }
}
