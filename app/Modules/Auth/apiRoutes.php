<?php

use App\Modules\Auth\AuthController;
use App\Modules\Auth\AuthModule;
use Illuminate\Support\Facades\Route;


Route::get('/me', [AuthController::class, 'me']);
Route::get('/roles', [AuthController::class, 'roles']);

Route::prefix('/registrar')->group(function () {
	Route::post('/particular', [AuthController::class, 'registrarParticular']);
	Route::post('/agencia', [AuthController::class, 'registrarAgencia']);
});

Route::post('/login', [AuthController::class, 'login']);

Route::get('/puedeRegistrarEmailEnOnboarding', [AuthController::class, 'puedeRegistrarEmailEnOnboarding']);


Route::post('/recuperar-password', [AuthController::class, 'recuperarPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::middleware('auth:api')->group(function() {
	Route::post('/logout', [AuthController::class, 'logout']);

	Route::patch('/cambiar-password', [AuthController::class, 'cambiarPassword']);
});

AuthModule::defineHttpRoutes();


