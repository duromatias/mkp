<?php

namespace App\Modules\Vehiculos;

use App\Base\Module;

class VehiculosModule extends Module {    
    
    public function bootApiRoutes() {
        $this->router()->group(['middleware'=>'auth:api'], function() {
            $this->router()->get('/marcas',                    [ VehiculosController::class, 'listarMarcas'           ]);
            $this->router()->get('/marcas/{modeloId}/modelos', [ VehiculosController::class, 'listarModelos'          ]);
            $this->router()->get('/modelos/{codia}/{year}',    [ VehiculosController::class, 'obtenerModelo'          ]);
            $this->router()->get('/precioSugerido',            [ VehiculosController::class, 'obtenerPrecioSugerido'  ]);
            $this->router()->get('/colores',                   [ VehiculosController::class, 'listarColores'          ]);
            $this->router()->get('/tipos-combustible',         [ VehiculosController::class, 'listarTiposCombustible' ]);
        });
    }
    
    public function register() {
    }
}
