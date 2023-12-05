<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AgregarBrandIdCodiaTablaPublicaciones extends Migration {

    public function up() {
		Schema::table('publicaciones', function (Blueprint $table) {
            $table->integer('brand_id');
            $table->integer('codia'   );
		});
    }

    public function down() {
		Schema::table('publicaciones', function (Blueprint $table) {
            $table->dropColumn('brand_id');
            $table->dropColumn('codia'   );
		});
    }
}
