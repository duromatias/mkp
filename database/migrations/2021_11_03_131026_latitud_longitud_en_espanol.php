<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LatitudLongitudEnEspanol extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('usuarios', function(Blueprint $table) {
            $table->renameColumn('latitude',  'latitud' );
            $table->renameColumn('longitude', 'longitud');
        });
        
        Schema::table('usuarios', function(Blueprint $table) {
            $table->decimal('latitud', 16, 14)->change();
            $table->decimal('longitud', 16, 14)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('usuarios', function(Blueprint $table) {
            $table->renameColumn('latitud',  'latitude' );
            $table->renameColumn('longitud', 'longitude');
        });
    }
}
