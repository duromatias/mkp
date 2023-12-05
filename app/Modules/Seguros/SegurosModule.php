<?php

namespace App\Modules\Seguros;

use App\Base\Module;
use App\Modules\Parametros\Parametro;use App\Modules\Parametros\ParametrosApp;use App\Modules\Seguros\Cotizaciones\CotizacionesModule;


class SegurosModule extends Module {
    
    public function register(): void {
        $this->provide(CotizacionesModule::class);
    }

     public function boot() {
        parent::boot();
        ParametrosApp::registrar(function() {
            return Parametro::getById(SegurosBusiness::ID_TELEFONO_CONTACTO_SEGUROS);
        });
    }
    
}
