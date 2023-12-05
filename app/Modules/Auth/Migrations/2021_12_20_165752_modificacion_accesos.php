<?php

use App\Modules\Auth\Models\Acceso;
use App\Modules\Auth\Models\AccesosRoles;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ModificacionAccesos extends Migration {

    public function up() {
        
        DB::statement("SET FOREIGN_KEY_CHECKS = 0;");
        DB::statement("TRUNCATE TABLE accesos_roles");
        DB::statement("TRUNCATE TABLE accesos");
        DB::statement("SET FOREIGN_KEY_CHECKS = 1;");
        
        Acceso::agregar('Inicio',                 '/',                                'home'                , 1);
        Acceso::agregar('Publicar',               '/publicaciones/agregar',           'post_add'            , 2);
        Acceso::agregar('Mis Publicaciones',      '/publicaciones/mis-publicaciones', 'article'             , 3);
        Acceso::agregar('Mis Datos',              '/usuario/mis-datos',               'account_circle'      , 4);
        Acceso::agregar('Usuarios',               '/usuarios',                        'group'               , 5);
        Acceso::agregar('Configuración',          '/configuracion',                   'settings'            , 6);
        Acceso::agregar('Términos y Condiciones', '/terminos-y-condiciones',          'format_list_numbered', 7);
        
        AccesosRoles::agregar(1, 1);
        AccesosRoles::agregar(1, 3);
        AccesosRoles::agregar(1, 4);
        AccesosRoles::agregar(1, 5);
        AccesosRoles::agregar(1, 6);
        AccesosRoles::agregar(1, 7);
        AccesosRoles::agregar(2, 1);
        AccesosRoles::agregar(2, 2);
        AccesosRoles::agregar(2, 3);
        AccesosRoles::agregar(2, 4);
        AccesosRoles::agregar(3, 1);
        AccesosRoles::agregar(3, 2);
        AccesosRoles::agregar(3, 3);
        AccesosRoles::agregar(3, 4);
        AccesosRoles::agregar(4, 1);
        AccesosRoles::agregar(4, 2);
        AccesosRoles::agregar(4, 3);
    }

    public function down() {
        //
    }
}
