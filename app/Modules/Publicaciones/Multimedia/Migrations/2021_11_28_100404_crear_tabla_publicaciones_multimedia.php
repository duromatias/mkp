<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrearTablaPublicacionesMultimedia extends Migration {

    public function up() {
        Schema::create('publicaciones_multimedia', function(Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('publicacion_id');
			$table->foreign('publicacion_id')->references('id')->on('publicaciones');

			$table->enum('tipo',       ['image',    'video'    ]);
			$table->enum('es_portada', ['SI',      'NO'       ])->default('NO');
			$table->enum('estado',     ['VISIBLE', 'ELIMINADO'])->default('VISIBLE');

			$table->timestamps();
            
        });
    }

    public function down() {
        Schema::dropIfExists('publicaciones_multimedia');
    }
}
