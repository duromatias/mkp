<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class FixPublicacionesPuertasEnum3 extends Migration {

    public function up() {
        DB::statement("
            ALTER TABLE publicaciones MODIFY
            COLUMN puertas ENUM(
                '2', 
                '2 (coupé)', 
                '3', 
                '4', 
                '5',
                '5 (rural)'
            )
        ");
        DB::statement("UPDATE publicaciones SET puertas = '2' WHERE puertas = '2 (coupé)';");
        DB::statement("UPDATE publicaciones SET puertas = '5' WHERE puertas = '5 (rural)';");
        
        DB::statement("
            ALTER TABLE publicaciones MODIFY
            COLUMN puertas ENUM(
                '2', 
                '3', 
                '4', 
                '5'
            )
        ");
    }

    public function down() {
    }
}
