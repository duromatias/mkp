<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AccesoMisPublicacionesAdministrador extends Migration {

    public function up() {
        DB::statement("
            INSERT INTO accesos_roles SET
                acceso_id = 2,
                rol_id = 1
        ");
    }

    public function down() {
        DB:statement("
            DELETE FROM accesos_roles 
            WHERE acceso_id = 2 
            AND rol_id = 1
        ");
    }
}
