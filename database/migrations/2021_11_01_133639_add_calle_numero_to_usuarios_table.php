<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCalleNumeroToUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->string('calle')->nullable();
            $table->string('numero')->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();

            $table->dropColumn('direccion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn([
            	'calle',
				'numero',
				'latitude',
				'longitude'
			]);

            $table->string('direccion')->nullable();
        });
    }
}
