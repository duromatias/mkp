<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AgregarAccesoCotizarSeguro extends Migration {

    public function up() {
        DB::insert("
            INSERT INTO accesos SET
                id          = 14,
                descripcion = 'Cotizar Seguro',
                ruta        = '/seguros/cotizar',
                icono       = NULL,
                orden       = 90,
                grupo       = NULL
        ");
    }

    public function down() {
        DB::delete("
            DELETE FROM accesos WHERE id = 14
        ");
    }
}
