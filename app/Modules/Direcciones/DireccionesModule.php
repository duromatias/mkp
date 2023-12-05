<?php

namespace App\Modules\Direcciones;

use App\Base\Module;
use Illuminate\Support\Facades\Route;

class DireccionesModule extends Module
{
    public function bootApiRoutes() {
		
        Route::get('localidades', [DireccionesController::class, 'listarLocalidades']);
        Route::get('provincias',  [DireccionesController::class, 'listarProvincias' ]);
		
	}
}