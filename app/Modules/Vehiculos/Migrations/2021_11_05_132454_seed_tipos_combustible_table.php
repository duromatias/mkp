<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SeedTiposCombustibleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('tipos_combustible')->insert([
        	[
        		'id' => 1,
				'descripcion' => 'Diesel'
			],
			[
				'id' => 2,
				'descripcion' => 'Eléctrico'
			],
			[
				'id' => 3,
				'descripcion' => 'Nafta'
			],
			[
				'id' => 4,
				'descripcion' => 'Nafta/GNC'
			],
			[
				'id' => 5,
				'descripcion' => 'GNC'
			],
			[
				'id' => 6,
				'descripcion' => 'Híbrido'
			],
			[
				'id' => 7,
				'descripcion' => 'Híbrido/Diesel'
			],
			[
				'id' => 8,
				'descripcion' => 'Híbrido/Nafta'
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
        DB::statement("TRUNCATE TABLE tipos_combustible"); // Para que el autoincrement vuelva a 1;
    }
}
