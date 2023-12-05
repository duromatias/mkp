<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AgregarAccesoCotizarSeguroARoles extends Migration {

    public function up() {
        foreach([1,2,3,4] as $rolId) {
            DB::insert("
                INSERT INTO accesos_roles SET
                    rol_id = '{$rolId}',
                    acceso_id = 14
            ");
        }
    }

    public function down() {
        DB::delete("
            DELETE FROM accesos_roles WHERE acceso_id = 14
        ");
    }
}
