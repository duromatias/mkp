<?php

use App\Modules\Users\UsersController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function() {
    
	Route::get ('/me',                    [ UsersController::class, 'me'                      ]);
	Route::put ('/me',                    [ UsersController::class, 'actualizarUsuarioActual' ]);
	Route::post('/me/actualizarPassword', [ UsersController::class, 'actualizarPassword'      ]);
	Route::post('/me/solicitarBaja',	  [ UsersController::class, 'solicitarBaja'			  ]);

	Route::get ('/{id}',                  [ UsersController::class, 'show'                    ]);
	Route::post('/{id}/actualizarRol',    [ UsersController::class, 'actualizarRol'           ]);
	Route::put ('/{id}/habilitar',        [ UsersController::class, 'habilitar'               ]);
	Route::put ('/{id}/deshabilitar',     [ UsersController::class, 'deshabilitar'            ]);
    
	Route::apiResource('/', UsersController::class)->only('index');
    
});
