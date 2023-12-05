<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDatosFinanciacionToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->string('apellido')->nullable()->after('nombre');
			$table->enum('sexo', ['M', 'F'])->nullable();
			$table->enum('estado_civil_id', [1, 2, 3, 4])->nullable();
			$table->enum('uso_vehiculo', [1, 2])->nullable();
			$table->date('fecha_nacimiento')->nullable();
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
            $table->dropColumn(['apellido', 'sexo', 'estado_civil_id', 'uso_vehiculo', 'fecha_nacimiento']);
        });
    }
}
