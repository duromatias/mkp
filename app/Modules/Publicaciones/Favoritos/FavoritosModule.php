<?php

namespace App\Modules\Publicaciones\Favoritos;

use App\Base\Module;
use Illuminate\Support\Facades\Route;

class FavoritosModule extends Module
{
	public static function defineHttpRoutes() {
		Route::middleware('auth:api')->group(function() {
			Route::post('/favoritos', [FavoritosController::class, 'create']);
			Route::delete('/favoritos', [FavoritosController::class, 'delete']);
		});
	}
}