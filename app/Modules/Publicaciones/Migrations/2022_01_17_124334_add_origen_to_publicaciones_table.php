<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrigenToPublicacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('publicaciones', function (Blueprint $table) {
            $table->enum('origen', ['Deusados', 'Externo']);
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
            $table->dropColumn('origen');
        });
    }
}
