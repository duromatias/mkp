<?php

use Illuminate\Database\Migrations\Migration;

class FixRolesDescripcion extends Migration {
    
    public function up() {
        DB::statement("UPDATE roles SET descripcion = 'Agencia'    WHERE id = '2'");
        DB::statement("UPDATE roles SET descripcion = 'Particular' WHERE id = '3'");
    }

    public function down() {
        //
    }
}
