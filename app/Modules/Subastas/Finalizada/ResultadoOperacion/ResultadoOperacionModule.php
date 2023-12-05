<?php

namespace App\Modules\Subastas\Finalizada\ResultadoOperacion;

use App\Base\Module;
use Illuminate\Support\Facades\Route;

class ResultadoOperacionModule extends Module {
       
    public function bootApiRoutes() {
        Route::post('/operacion-realizada', [ResultadoOperacionController::class, 'actualizarOperacionRealizada'])->middleware('auth:api');  
        Route::post('/calificacion'       , [ResultadoOperacionController::class, 'actualizarCalificacion'])      ->middleware('auth:api');        
        Route::post('/observaciones'      , [ResultadoOperacionController::class, 'actualizarObservaciones'])     ->middleware('auth:api');
    }
    
}
