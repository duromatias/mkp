<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class TiposCombustibleCambioOrden extends Migration {

    public function up()  {
        
        static::actualizar(1, 'Nafta'         );
        static::actualizar(2, 'Nafta/GNC'     );
        static::actualizar(3, 'Diesel'        );
        static::actualizar(4, 'GNC'           );
        static::actualizar(5, 'Eléctrico'     );
        static::actualizar(6, 'Híbrido'       );
        static::actualizar(7, 'Híbrido/Diesel');
        static::actualizar(8, 'Híbrido/Nafta' );
    }
    
    static public function actualizar(int $id, string $descripcion) {
        DB::statement("
            UPDATE tipos_combustible SET 
                descripcion = '{$descripcion}' 
            WHERE id = '{$id}'
        ");
    }


    public function down() {
        //
    }
}
