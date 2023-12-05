<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SubirOrdenesAccesos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('accesos')->where(['orden' => 1])->update(['orden' => 10]);
		DB::table('accesos')->where(['orden' => 2])->update(['orden' => 20]);
		DB::table('accesos')->where(['orden' => 3])->update(['orden' => 30]);
		DB::table('accesos')->where(['orden' => 4])->update(['orden' => 40]);
		DB::table('accesos')->where(['orden' => 5])->update(['orden' => 50]);
		DB::table('accesos')->where(['orden' => 6])->update(['orden' => 60]);
		DB::table('accesos')->where(['orden' => 7])->update(['orden' => 70]);
		DB::table('accesos')->where(['orden' => 8])->update(['orden' => 80]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		DB::table('accesos')->where(['orden' => 10])->update(['orden' => 1]);
		DB::table('accesos')->where(['orden' => 20])->update(['orden' => 2]);
		DB::table('accesos')->where(['orden' => 30])->update(['orden' => 3]);
		DB::table('accesos')->where(['orden' => 40])->update(['orden' => 4]);
		DB::table('accesos')->where(['orden' => 50])->update(['orden' => 5]);
		DB::table('accesos')->where(['orden' => 60])->update(['orden' => 6]);
		DB::table('accesos')->where(['orden' => 70])->update(['orden' => 7]);
		DB::table('accesos')->where(['orden' => 80])->update(['orden' => 8]);
    }
}
