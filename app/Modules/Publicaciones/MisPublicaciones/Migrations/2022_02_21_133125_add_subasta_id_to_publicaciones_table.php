<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubastaIdToPublicacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('publicaciones', function (Blueprint $table) {
            $table->foreignId('subasta_id')->nullable()->constrained('subastas');
            $table->decimal('precio_base', 15)->nullable();
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
            $table->dropForeign('publicaciones_subasta_id_foreign');
            $table->dropColumn(['subasta_id', 'precio_base']);
        });
    }
}
