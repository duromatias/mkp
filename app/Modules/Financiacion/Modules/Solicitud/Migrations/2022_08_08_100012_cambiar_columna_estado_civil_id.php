<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CambiarColumnaEstadoCivilId extends Migration {

    public function up() {
        Schema::table('usuarios', function (Blueprint $table) {
			$table->integer('estado_civil_id')->nullable()->change();
        });
    }


    public function down() {
        Schema::table('usuarios', function (Blueprint $table) {
			$table->enum('estado_civil_id', [1, 2, 3, 4])->nullable()->change();
        });
    }
}
