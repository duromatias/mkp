<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AgregarColumnaNombreTablaParametros extends Migration {

    public function up() {
        Schema::table('parametros', function(Blueprint $table) {
            $table->string('nombre', 255)->nullable()->default(null);
        });
    }

    public function down() {
        Schema::table('parametros', function(Blueprint $table) {
            $table->dropColumn('nombre');
        });
    }
}
