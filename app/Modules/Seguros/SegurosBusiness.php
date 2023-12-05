<?php

namespace App\Modules\Seguros;

use App\Modules\Parametros\Parametro;

class SegurosBusiness
{
    const ID_TELEFONO_CONTACTO_SEGUROS = 13;

    static private function obtenerTelefonoContactoSeguros() {
        return Parametro::getById(static::ID_TELEFONO_CONTACTO_SEGUROS)->valor;
    }
}
