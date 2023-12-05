<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ParametrosVigencia extends Migration
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
				'id' => 6,
				'descripcion' => 'Cantidad de días hasta perder vigencia de una publicación',
				'valor' => 30
			],
			[
				'id' => 7,
				'descripcion' => 'Cantidad de días previos a la pérdida de vigencia de una publicación en que se realiza notificación',
				'valor' => 3
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
        DB::table('parametros')->whereIn('id', [6, 7])->delete();
    }
}
