<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ParametrosOfertasIncremento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		DB::table('parametros')->insert([
			[
				'id' => 4,
				'descripcion' => 'Monto en dólares del incremento de una oferta en la subasta',
				'valor' => 100
			],
			[
				'id' => 5,
				'descripcion' => 'Monto en pesos del incremento de una oferta en la subasta',
				'valor' => 20000
			]
		]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('parametros')->whereIn('id', [4, 5])->delete();
    }
}
