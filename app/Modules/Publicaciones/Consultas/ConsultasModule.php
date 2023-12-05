<?php

namespace App\Modules\Publicaciones\Consultas;

use App\Base\Module;
use Illuminate\Support\Facades\Route;

class ConsultasModule extends Module
{
	public static function defineHttpRoutes() {
		Route::prefix('consultas')->group(function() {
			Route::post('', [ConsultasController::class, 'store']);


			Route::middleware('auth:api')->group(function() {
				Route::get('', [ConsultasController::class, 'index']);

				Route::get('{consultaId}', [ConsultasController::class, 'getOne']);
				Route::post('{id}:resolver', [ConsultasController::class, 'resolver']);
				Route::post('{id}:responder', [ConsultasController::class, 'responder']);
			});

		});
	}

}