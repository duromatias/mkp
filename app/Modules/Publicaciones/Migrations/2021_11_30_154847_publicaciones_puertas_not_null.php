<?php

use Illuminate\Database\Migrations\Migration;

class PublicacionesPuertasNotNull extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        DB::statement("
            ALTER TABLE publicaciones MODIFY
            COLUMN puertas ENUM(
                '2 (coupé)', 
                '3', 
                '4', 
                '5 (rural)'
            ) NOT NULL
        ");
    }

    public function down() {
        //
    }
}
