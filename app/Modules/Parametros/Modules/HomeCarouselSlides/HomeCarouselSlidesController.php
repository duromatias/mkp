<?php

namespace App\Modules\Parametros\Modules\HomeCarouselSlides;

use App\Http\Controllers\Controller;
use App\Modules\Parametros\Modules\HomeCarouselSlides\Businesses\HomeCarouselSlideBusiness;
use App\Modules\Parametros\Modules\HomeCarouselSlides\Dtos\HomeCarouselSlideDto;
use App\Modules\Parametros\Modules\HomeCarouselSlides\Models\HomeCarouselSlide;
use App\Modules\Parametros\Modules\HomeCarouselSlides\Requests\UpdateHomeCarouselSlideRequest;
use App\Modules\Parametros\Modules\HomeCarouselSlides\Resources\HomeCarouselSlideResource;

class HomeCarouselSlidesController extends Controller
{
	public function index() {
		$homeCarouselSlides = HomeCarouselSlideBusiness::listar();

		return HomeCarouselSlideResource::collection($homeCarouselSlides);
	}

	public function show(HomeCarouselSlide $homeCarouselSlide) {
		return new HomeCarouselSlideResource($homeCarouselSlide);
	}

	public function update(HomeCarouselSlide $homeCarouselSlide, UpdateHomeCarouselSlideRequest $request) {
		$homeCarouselSlideDto = HomeCarouselSlideDto::fromArray($request->validated());

		$homeCarouselSlide = HomeCarouselSlideBusiness::update($homeCarouselSlide, $homeCarouselSlideDto);

		return new HomeCarouselSlideResource($homeCarouselSlide);
	}
}