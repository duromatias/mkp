<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChageSubastaOrderOnAccesosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	// Subastas
        DB::table('accesos')->where('id', '=', 9)->update(
        	['orden' => 4]
		);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		// Subastas
		DB::table('accesos')->where('id', '=', 9)->update(
			['orden' => 9]
		);
    }
}
