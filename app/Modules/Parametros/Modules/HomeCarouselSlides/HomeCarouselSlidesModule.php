<?php

namespace App\Modules\Parametros\Modules\HomeCarouselSlides;

use App\Base\Module;

class HomeCarouselSlidesModule extends Module
{
	public $routePrefix = 'home-carousel-slides';
    
	public function bootApiRoutes() {
        $this->router()->get('', [HomeCarouselSlidesController::class, 'index']);
        $this->router()->get('{homeCarouselSlide}', [HomeCarouselSlidesController::class, 'show']);
        $this->router()->middleware('auth:api')->group(function() {
            $this->router()->post('{homeCarouselSlide}', [HomeCarouselSlidesController::class, 'update']);
        });
	}
}