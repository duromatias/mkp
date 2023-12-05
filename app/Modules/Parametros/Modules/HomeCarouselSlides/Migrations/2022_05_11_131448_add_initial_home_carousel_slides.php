<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddInitialHomeCarouselSlides extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('home_carousel_slides')->insert([
        	[
        		'id' => 1,
				'titulo' => 'La venta de tus usados',
				'detalle' => 'a un click de distancia',
				'link' => null,
				'orden' => 1,
				'imagen_desktop_file_name' => 'desktop-image-1.png',
				'imagen_mobile_file_name' => 'mobile-image-1.png'
 			],
			[
				'id' => 2,
				'titulo' => 'La venta de tus usados',
				'detalle' => 'a un click de distancia',
				'link' => null,
				'orden' => 2,
				'imagen_desktop_file_name' => 'desktop-image-2.png',
				'imagen_mobile_file_name' => 'mobile-image-2.png'
			],
			[
				'id' => 3,
				'titulo' => 'La venta de tus usados',
				'detalle' => 'a un click de distancia',
				'link' => null,
				'orden' => 3,
				'imagen_desktop_file_name' => 'desktop-image-3.png',
				'imagen_mobile_file_name' => 'mobile-image-3.png'
			]
		]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Db::table('home_carousel_slides')
			->whereIn('id', [1, 2, 3])
			->delete();
    }
}
