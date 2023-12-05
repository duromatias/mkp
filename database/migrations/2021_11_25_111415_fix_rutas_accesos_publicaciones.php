<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class FixRutasAccesosPublicaciones extends Migration {
    
    public function up() {
        DB::statement("UPDATE accesos SET ruta = '\/publicaciones\/agregar'           WHERE descripcion = 'Publicar'         ");
        DB::statement("UPDATE accesos SET ruta = '\/publicaciones\/mis-publicaciones' WHERE descripcion = 'Mis Publicaciones'");
    }

    public function down() {
        //
    }
}
