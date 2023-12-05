<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AgregarComentarioColumnaProcesoId extends Migration {

    public function up() {
        Schema::table('log_http', function(Blueprint $table) {
            $comentario = implode('', [
                'Se almacena un identificador del proceso para agrupar ',
                'las solicitudes de api que se realizan',
                'durante la misma ejecución'
            ]);
            
            $table->string('procesoId')->comment($comentario)->change();
        });
    }

    public function down() {
        
    }
}
