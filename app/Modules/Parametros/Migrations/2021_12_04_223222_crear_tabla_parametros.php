<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrearTablaParametros extends Migration {

    public function up() {
        Schema::create('parametros', function(Blueprint $table) {
            $table->id();
            $table->string('descripcion', 255);
			$table->string('valor' ,      255);
        });
    }

    public function down() {
        Schema::dropIfExists('parametros');
    }
}
