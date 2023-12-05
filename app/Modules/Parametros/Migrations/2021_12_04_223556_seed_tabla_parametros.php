<?php

use App\Modules\Parametros\Parametro;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SeedTablaParametros extends Migration {

    public function up() {
        Parametro::crear('Porcentaje para etiqueta de oportunidad', 10);
    }

    public function down() {
        DB::statement("TRUNCATE TABLE parametros");
    }
}
