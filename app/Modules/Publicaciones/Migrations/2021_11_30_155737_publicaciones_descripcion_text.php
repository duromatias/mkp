<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PublicacionesDescripcionText extends Migration {

    public function up() {
		Schema::table('publicaciones', function (Blueprint $table) {
            $table->text('descripcion')->change();
		});
    }

    public function down() {
        //
    }
}
