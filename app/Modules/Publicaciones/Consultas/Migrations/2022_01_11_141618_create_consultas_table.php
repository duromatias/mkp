<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsultasTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('consultas', function (Blueprint $table) {
			$table->id();
			$table->foreignId('publicacion_id')->constrained('publicaciones');
			$table->foreignId('usuario_origen_id')->nullable()->constrained('usuarios');
			$table->foreignId('usuario_destino_id')->constrained('usuarios');
			$table->string('nombre');
			$table->string('email');
			$table->string('telefono');
			$table->string('consulta');
			$table->string('respuesta')->nullable();
			$table->enum('apto_credito', ['Si', 'No', 'No aplica', 'Sin información']);
			$table->enum('estado', ['Pendiente', 'Resuelta']);
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
		Schema::dropIfExists('consultas');
	}
}
