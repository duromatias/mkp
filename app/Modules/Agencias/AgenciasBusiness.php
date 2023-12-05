<?php

namespace App\Modules\Agencias;

use App\Modules\Onboarding\Models\Business;

class AgenciasBusiness
{
    public static function obtener(string $nombreAgencia){
        return Business::getByName($nombreAgencia);
    }
}
