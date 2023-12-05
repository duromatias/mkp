<?php

namespace App\Modules\Seguros\Cotizaciones;

use App\Base\Module;


class CotizacionesModule extends Module {
    
    public function bootApiRoutes() {
        $this->router()->get('/listar',                        [ CotizacionesController::class, 'listar'              ]);
        $this->router()->get('/listar-años',                   [ CotizacionesController::class, 'listarAños'          ]);
        $this->router()->get('/listar-modelos-por-año/{anio}', [ CotizacionesController::class, 'listarModelosPorAño' ]);
        $this->router()->get('/listar-localidades',            [ CotizacionesController::class, 'listarLocalidades'   ]);
        $this->router()->get(
            '/listar-localidades-por-codigo-postal',
            [ CotizacionesController::class, 'listarLocalidadesPorCodigoPostal' ]
        );
    }
    
}
