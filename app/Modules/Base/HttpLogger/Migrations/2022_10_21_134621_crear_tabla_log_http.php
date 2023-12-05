<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrearTablaLogHttp extends Migration {

    public function up() {
        Schema::create('log_http', function(Blueprint $table) {
            $table->id();
            $table->string  ('procesoId'       );
            $table->integer ('usuarioId'       )->nullable();
            $table->string  ('email'           )->nullable();
            $table->string  ('rutaBase'        );
            $table->string  ('metodo'          );
            $table->string  ('uri'             );
            $table->text    ('peticionHeaders' )->nullable();
            $table->text    ('peticionData'    )->nullable();
            $table->text    ('respuestaHeaders')->nullable();
            $table->longText('respuestaData'   )->nullable();
            $table->float   ('tsInicio', 14, 4, true)->nullable();
            $table->float   ('tsTotal' ,  8, 4, true)->nullable();
            $table->timestamps();
        });
        
        Schema::table('log_http', function(Blueprint $table) {
            $table->index('procesoId');
            $table->index('usuarioId');
            $table->index('email'    );
            $table->index('rutaBase' );
            $table->index('metodo'   );
            $table->index('uri'      );
        });
    }

    public function down() {
        Schema::drop('log_http');
    }
}
