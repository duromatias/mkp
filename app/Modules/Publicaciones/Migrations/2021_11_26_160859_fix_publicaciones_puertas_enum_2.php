<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class FixPublicacionesPuertasEnum2 extends Migration {

    public function up() {
        DB::statement("
            ALTER TABLE publicaciones MODIFY
            COLUMN puertas ENUM(
                '2 (coupé)', 
                '3', 
                '4', 
                '5 (rural)'
            )
        ");
    }

    public function down() {
        //
    }
}
