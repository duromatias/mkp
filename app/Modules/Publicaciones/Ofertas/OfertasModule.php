<?php

namespace App\Modules\Publicaciones\Ofertas;

use App\Base\Module;
use Illuminate\Support\Facades\Route;

class OfertasModule extends Module
{
	public static function defineHttpRoutes() {
		Route::middleware('auth:api')->group(function () {
			Route::get('/ofertas', [OfertasController::class, 'index']);
			Route::post('/ofertas', [OfertasController::class, 'create']);
		});
	}
}