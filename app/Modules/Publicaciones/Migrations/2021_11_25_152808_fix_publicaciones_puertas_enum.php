<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class FixPublicacionesPuertasEnum extends Migration {

    public function up() {
        DB::statement("
            ALTER TABLE publicaciones MODIFY
            COLUMN puertas ENUM(
                '2 (Coupé)', 
                '3', 
                '4', 
                '5 (Rural)'
            )
        ");
    }

    public function down() {
        //
    }
}
