<?php

namespace App\Modules\Subastas;

use App\Base\Module;
use App\Modules\Subastas\Cierre\CierreModule;
use App\Modules\Subastas\Finalizada\FinalizadaModule;
use App\Modules\Subastas\Commands\NotificacionInicioInscripcion;
use App\Modules\Subastas\Commands\NotificarInicioOfertas;
use Illuminate\Support\Facades\Route;

class SubastasModule extends Module
{
	public function bootApiRoutes() {
		Route::post('/', [SubastasController::class, 'create']);
		Route::get('/', [SubastasController::class, 'index']);
		Route::put('{id}', [SubastasController::class, 'update']);
		Route::get('{id}', [SubastasController::class, 'show']);
		Route::post('{id}:cancelar', [SubastasController::class, 'cancelar']);
        Route::get('/*/disponible', [SubastasController::class, 'obtenerDisponible']);
	}

	public function bootSchedule() {
        $this->scheduler()->command(NotificarInicioOfertas::class)->dailyAt('09:00');
		$this->scheduler()->command(NotificacionInicioInscripcion::class)->dailyAt('09:00');
	}
    
    public function register(): void {
        $this->provide(CierreModule     ::class);        
        $this->provide(FinalizadaModule ::class);
    }
}