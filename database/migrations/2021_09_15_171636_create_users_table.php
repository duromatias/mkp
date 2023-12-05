<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('usuarios', function (Blueprint $table) {
			$table->id();
			$table->string('email')->unique();
			$table->string('password');
			$table->string('nombre');
			$table->string('telefono');
			$table->string('direccion');
			$table->string('localidad');
			$table->string('provincia');
			$table->string('tipo_doc');
			$table->string('nro_doc');

			$table->foreignId('rol_id')->constrained('roles');

			$table->rememberToken();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('usuarios');
	}
}
