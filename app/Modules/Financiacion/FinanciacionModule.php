<?php

namespace App\Modules\Financiacion;

use App\Base\Module;
use App\Modules\Financiacion\Modules\Solicitud\SolicitudModule;
use Illuminate\Support\Facades\Route;

class FinanciacionModule extends Module
{
	public function bootApiRoutes() {
		Route::middleware('auth:api')->group(function() {
			Route::get('verificar', [FinanciacionController::class, 'verificar']);
		});
	}

	public function register() {
		$this->provide(SolicitudModule::class);
	}
}