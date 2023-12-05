<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RenameAccesoPublicacionesToVehiculos extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::table('accesos')->where('descripcion', '=', 'Publicar')->update(['descripcion' => 'Vehículos']);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::table('accesos')->where('descripcion', '=', 'Vehículos')->update(['descripcion' => 'Publicar']);
	}
}
