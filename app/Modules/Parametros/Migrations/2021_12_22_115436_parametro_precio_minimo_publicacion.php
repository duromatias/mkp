<?php

use App\Modules\Parametros\Parametro;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class ParametroPrecioMinimoPublicacion extends Migration {
    
    public function up() {
        Parametro::crear('Precio mínimo en pesos de una publicación', 50000);
    }

    public function down() {
        DB::statement("DELETE FROM parametros WHERE id = 2");
        Schema::table('parametros', function(Blueprint $table) {
            $table->increments('id')->startingValue(2);
        });
    }
}
