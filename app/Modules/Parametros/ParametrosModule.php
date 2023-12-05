<?php

namespace App\Modules\Parametros;

use App\Base\Module;
use App\Modules\Parametros\Modules\HomeCarouselSlides\HomeCarouselSlidesModule;

class ParametrosModule extends Module {
    
	public function bootApiRoutes() {        
		$this->router()->middleware('auth:api')->group(function() {
			$this->router()->get('/', [ParametrosController::class, 'index']);
			$this->router()->put('/mass-update', [ParametrosController::class, 'massUpdate']);
		});
	}
    
    public function boot() {
        parent::boot();
        ParametrosApp::registrar(function() {
            return [
                Parametro::getById(Parametro::ID_FACEBOOK ),
                Parametro::getById(Parametro::ID_INSTAGRAM),
                Parametro::getById(Parametro::ID_LINKEDIN ),
            ];
        });
    }

	public function register() {        
		$this->provide(HomeCarouselSlidesModule::class);
	}
}
