<?php

namespace App\Modules\Direcciones;

use App\Modules\Prendarios\Models\Localidad;

class DireccionesBusiness    
{
    static public function listarLocalidades(int $page, int $limit, array $filtros = [], array $ordenes = [], $opciones = []) {
        return Localidad::listar($page, $limit, $filtros, $ordenes, $opciones);
    }
    
    static public function listarProvincias() {
        return Provincia::listar();
    }
}