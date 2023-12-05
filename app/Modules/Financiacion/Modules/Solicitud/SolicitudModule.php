<?php

namespace App\Modules\Financiacion\Modules\Solicitud;

use Illuminate\Support\Facades\Route;

class SolicitudModule extends \App\Base\Module
{
	public function bootApiRoutes() {
		Route::get ('{publicacion}/monto-maximo-financiable', 	[ SolicitudController::class, 'obtenerMontoMaximoFinanciable' ]);
		Route::get ('{publicacion}/cuotas',                     [ SolicitudController::class, 'obtenerCuotas'                 ]);
		Route::get ('{publicacion}/cuotas-y-montos',            [ SolicitudController::class, 'obtenerCuotasYMontos'          ]);
		Route::get ('{publicacion}/personas-por-documento',     [ SolicitudController::class, 'obtenerPersonasPorDocumento'   ]);

		Route::middleware('auth:api')->group(function() {
			Route::post('/datos-financiacion/actualizar',       [ SolicitudController::class, 'actualizarDatosFinanciacion'   ]);
			Route::get ('/datos-financiacion/datos-auxiliares', [ SolicitudController::class, 'obtenerDatosAuxiliares'        ]);
            Route::get ('/datos-financiacion/localidades',      [ SolicitudController::class, 'listarLocalidades'             ]);
			Route::get ('{publicacion}/cuotas-por-usuario',     [ SolicitudController::class, 'obtenerCuotasPorUsuario'       ]);
			Route::get ('{publicacion}/puede-generar',          [ SolicitudController::class, 'puedeGenerar'                  ]);
			Route::post('{publicacion}/generar',                [ SolicitudController::class, 'generar'                       ]);
			Route::get ('{publicacion}/operaciones/{operacion}',[ SolicitudController::class, 'obtenerDatos'                  ]);
			Route::post('{publicacion}/notificar-error-al-solicitar-cuotas-por-usuario',[ SolicitudController::class, 'notificarErrorAlSolicitarCuotasPorUsuario'                  ]);
			Route::post('{publicacion}/notificar-producto-faltante',[ SolicitudController::class, 'notificarPoductoFaltante']);
        });
	}
}