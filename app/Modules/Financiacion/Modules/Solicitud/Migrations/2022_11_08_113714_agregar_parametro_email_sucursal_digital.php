<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AgregarParametroEmailSucursalDigital extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('parametros')->insert([
			'id' => 12,
			'descripcion' => 'Email sucursal Digital',
			'valor' => 'contact@decreditos.com'
		]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('parametros')->where('id', '=', 12)->delete();
    }
}
