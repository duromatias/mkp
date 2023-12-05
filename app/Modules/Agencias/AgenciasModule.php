<?php

namespace App\Modules\Agencias;

use App\Base\Module;
use App\Modules\Agencias\Imagenes\ImagenesModule;

class AgenciasModule extends Module
{

    public function bootApiRoutes(){
       $this->router()->get('/obtener',     [AgenciasController::class, 'obtener']);
       $this->router()->post('/actualizar', [AgenciasController::class, 'actualizar']);
    }

    public function register() {
        $this->provide(ImagenesModule::class);
    }
}
