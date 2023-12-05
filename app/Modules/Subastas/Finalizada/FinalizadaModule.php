<?php

namespace App\Modules\Subastas\Finalizada;

use App\Base\Module;
use App\Modules\Subastas\Finalizada\ResultadoOperacion\ResultadoOperacionModule;

class FinalizadaModule extends Module {
    
    public function register(): void {
        $this->provide(ResultadoOperacionModule::class);        

    }
}
