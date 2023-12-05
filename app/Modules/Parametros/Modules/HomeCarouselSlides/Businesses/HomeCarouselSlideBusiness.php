<?php

namespace App\Modules\Parametros\Modules\HomeCarouselSlides\Businesses;

use App\Modules\Parametros\Modules\HomeCarouselSlides\Dtos\HomeCarouselSlideDto;
use App\Modules\Parametros\Modules\HomeCarouselSlides\Models\HomeCarouselSlide;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Kodear\Laravel\ModelStorage\ModelStorage;
use Kodear\Laravel\ModelStorage\ModelStorageFactory;

class HomeCarouselSlideBusiness
{
	public static function listar() {
		return HomeCarouselSlide::listar();
	}

	public static function update(HomeCarouselSlide $homeCarouselSlide, HomeCarouselSlideDto $homeCarouselSlideDto) {
		$data = Arr::only((array) $homeCarouselSlideDto, ['titulo', 'detalle', 'link', 'orden']);


		if (isset($homeCarouselSlideDto->imagen_desktop)) {
			$extension = $homeCarouselSlideDto->imagen_desktop->extension();
			$data['imagen_desktop_file_name'] = "desktop-image-{$homeCarouselSlide->id}.{$extension}";

			Storage::disk('public')
				->putFileAs('home-carousel-slides', $homeCarouselSlideDto->imagen_desktop, $data['imagen_desktop_file_name']);
		}

		if (isset($homeCarouselSlideDto->imagen_mobile)) {
			$extension = $homeCarouselSlideDto->imagen_mobile->extension();
			$data['imagen_mobile_file_name'] = "mobile-image-{$homeCarouselSlide->id}.{$extension}";

			Storage::disk('public')
				->putFileAs('home-carousel-slides', $homeCarouselSlideDto->imagen_mobile, $data['imagen_mobile_file_name']);
		}

		$homeCarouselSlide->fill((array) $data);
		return $homeCarouselSlide->guardar();
	}

	public static function getImageUrl(string $fileName) {
		return url("storage/home-carousel-slides/$fileName");
	}
}