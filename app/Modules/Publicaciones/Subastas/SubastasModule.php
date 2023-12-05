<?php

namespace App\Modules\Publicaciones\Subastas;

use Illuminate\Support\Facades\Route;

class SubastasModule extends \App\Base\Module
{
	public static function defineHttpRoutes() {
		Route::get('/home',              [ SubastasController::class, 'listarHome'             ]);
		Route::get('/mis-publicaciones', [ SubastasController::class, 'listarMisPublicaciones' ]);
		Route::get('/mis-pujas',         [ SubastasController::class, 'listarMisPujas'         ]);
		Route::get('/mis-favoritos',     [ SubastasController::class, 'listarMisFavoritos'     ]);
	}
}