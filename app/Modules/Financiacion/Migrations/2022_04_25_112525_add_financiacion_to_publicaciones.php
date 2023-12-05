<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFinanciacionToPublicaciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('publicaciones', function (Blueprint $table) {
            $table->boolean('financiacion')->default(false);
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
            $table->dropColumn('financiacion');
        });
    }
}
