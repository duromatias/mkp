<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AgregarMultimediaExtension extends Migration {

    public function up() {
        Schema::table('publicaciones_multimedia', function(Blueprint $table) {
            $table->string('extension', 10)
                ->default('')
                ->nullable(false)
                ->after('tipo');
        });
    }

    public function down() {
        Schema::table('publicaciones_multimedia', function(Blueprint $table) {
            $table->dropColumn('extension');
        });
    }
}
