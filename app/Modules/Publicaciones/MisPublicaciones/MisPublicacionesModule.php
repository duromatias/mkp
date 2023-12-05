<?php

namespace App\Modules\Publicaciones\MisPublicaciones;

use App\Base\Module;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Route;

class MisPublicacionesModule extends Module {
    
    public static function defineHttpRoutes() {
        Route::middleware('auth:api')->group(function() {
            Route::post  ('/*/mis-publicaciones',                 [ MisPublicacionesController::class, 'store'                     ]);
            Route::get   ('/*/mis-publicaciones',                 [ MisPublicacionesController::class, 'index'                     ]);
            Route::get   ('/*/mis-publicaciones/precioMinimo',    [ MisPublicacionesController::class, 'obtenerPrecioMinimo'       ]);
            Route::get   ('/*/mis-publicaciones/marcas',          [ MisPublicacionesController::class, 'obtenerMarcasDisponibles'  ]);
            Route::get   ('/*/mis-publicaciones/modelos',         [ MisPublicacionesController::class, 'obtenerModelosDisponibles' ]);
			Route::get('/*/mis-publicaciones/opciones-formulario', [MisPublicacionesController::class, 'opcionesFormulario'		   ]);
			Route::get   ('/*/mis-publicaciones/{id}',            [ MisPublicacionesController::class, 'show'                      ]);
			Route::post  ('/*/mis-publicaciones/{id}/actualizar', [ MisPublicacionesController::class, 'actualizar'                ]);
			Route::delete('/*/mis-publicaciones/{id}',            [ MisPublicacionesController::class, 'eliminar'                  ]);
        });
    }
    
    public function boot() {
        parent::boot();
        $this->onSchedule(function (Schedule $schedule) {
            $schedule->command('notify:publicaciones-proximas-vencer')->dailyAt('09:00');
            $schedule->command('notify:publicaciones-vencidas')->dailyAt('09:00');
        });
    }
}
