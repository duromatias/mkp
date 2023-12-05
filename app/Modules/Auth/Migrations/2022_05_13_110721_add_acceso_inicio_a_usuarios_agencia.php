<?php

use App\Modules\Auth\Models\AccesosRoles;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccesoInicioAUsuariosAgencia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        AccesosRoles::create([
        	'acceso_id' => 1,
			'rol_id' => 2
		]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        AccesosRoles::where([
        	'acceso_id' => 1,
			'rol_id' => 2
		])->delete();
    }
}
