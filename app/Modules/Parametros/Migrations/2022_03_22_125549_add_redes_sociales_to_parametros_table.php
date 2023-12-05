<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddRedesSocialesToParametrosTable extends Migration
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
        		'id' => 8,
				'descripcion' => 'Facebook',
				'valor' => 'https://www.facebook.com/decreditos'
			],
			[
				'id' => 9,
				'descripcion' => 'Instagram',
				'valor' => 'https://www.instagram.com/decreditos/',
			],
			[
				'id' => 10,
				'descripcion' => 'Linkedin',
				'valor'	=> 'https://www.linkedin.com/company/decreditos-sa/'
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
        DB::table('parametros')->whereIn('id', [8, 9, 10])->delete();
    }
}
