<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NullableFieldsUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('usuarios', function (Blueprint $table) {
			$table->string('password')->nullable()->change();
			$table->string('nombre')->nullable()->change();
			$table->string('telefono')->nullable()->change();
			$table->string('direccion')->nullable()->change();
			$table->string('localidad')->nullable()->change();
			$table->string('provincia')->nullable()->change();
			$table->string('tipo_doc')->nullable()->change();
			$table->string('nro_doc')->nullable()->change();

			$table->enum('estado', ['HABILITADO', 'DESHABILITADO']);
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('usuarios', function (Blueprint $table) {
			$table->string('password')->nullable(false)->change();
			$table->string('nombre')->nullable(false)->change();
			$table->string('telefono')->nullable(false)->change();
			$table->string('direccion')->nullable(false)->change();
			$table->string('localidad')->nullable(false)->change();
			$table->string('provincia')->nullable(false)->change();
			$table->string('tipo_doc')->nullable(false)->change();
			$table->string('nro_doc')->nullable(false)->change();

			$table->dropColumn('estado');
		});
    }
}
