<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemovePrecioCompraDirectaOnPublicacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
		Schema::table('publicaciones', function (Blueprint $table) {
			$table->dropColumn('precio_compra_directa');

			$table->float('precio')->change();
			$table->float('precio_sugerido')->change();
		});
	}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('publicaciones', function(Blueprint $table) {
			$table->decimal('precio_compra_directa');

			$table->decimal('precio')->change();
			$table->decimal('precio_sugerido')->change();

		});
    }
}
