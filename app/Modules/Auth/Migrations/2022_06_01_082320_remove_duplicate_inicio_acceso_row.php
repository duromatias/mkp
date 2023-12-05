<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RemoveDuplicateInicioAccesoRow extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		DB::statement('DELETE ar1 FROM accesos_roles ar1
			INNER JOIN accesos_roles ar2 
			WHERE 
				ar1.id > ar2.id AND 
				ar1.acceso_id = ar2.acceso_id AND
    			ar1.rol_id = ar2.rol_id;'
		);
	}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
