<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddDominioToPublicacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('publicaciones', function (Blueprint $table) {
            $table->string('dominio')->nullable();
        });

		DB::table('publicaciones')->update(['dominio' => 'aaa000']);

		Schema::table('publicaciones', function (Blueprint $table) {
			$table->string('dominio')->nullable(false)->change();
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('publicaciones', function (Blueprint $table) {
            $table->dropColumn('dominio');
        });
    }
}
