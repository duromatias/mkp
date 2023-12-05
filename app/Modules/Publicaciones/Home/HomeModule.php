<?php

namespace App\Modules\Publicaciones\Home;

use App\Base\Module;
use Illuminate\Support\Facades\Route;

class HomeModule extends Module {
    
    public static function defineHttpRoutes() {
        Route::middleware('auth:api')->group(function() {
        });
        Route::get('/',        				[ HomeController::class, 'index' ]);
		Route::get('/{id}',    				[ HomeController::class, 'show'  ]);
		Route::post('/{id}/contarClick',	[ HomeController::class, 'contarClick']);
        Route::get('/*/marcas',  			[ HomeController::class, 'obtenerMarcasDisponibles'  ]);
        Route::get('/*/modelos', 			[ HomeController::class, 'obtenerModelosDisponibles' ]);
    }
}
