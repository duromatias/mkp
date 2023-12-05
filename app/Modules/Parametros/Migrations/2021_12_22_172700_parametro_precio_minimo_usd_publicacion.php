<?php

use App\Modules\Parametros\Parametro;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class ParametroPrecioMinimoUsdPublicacion extends Migration {
    
    public function up() {
        Parametro::crear('Precio mínimo en dólares de una publicación', 250);
    }

    public function down() {
        DB::statement("DELETE FROM parametros WHERE id = 3");
        Schema::table('parametros', function(Blueprint $table) {
            $table->increments('id')->startingValue(2);
        });
    }
}
