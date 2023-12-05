<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomeCarouselTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_carousel_slides', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('detalle');
            $table->string('link')->nullable();
            $table->string('imagen_desktop_file_name');
            $table->string('imagen_mobile_file_name');
            $table->integer('orden');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('home_carousel');
    }
}
