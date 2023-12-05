<?php

namespace App\Modules\Hubspot;

use App\Modules\Users\Models\User;class HubspotBusiness
{
    public static function actualizarRedes(User $usuario, ?string $facebook, ?string $instagram){
        $codigoAgencia = $usuario->onboardingUser->business->code;
        if($facebook == null) $facebook = "";
        if($instagram == null) $instagram = "";
        return (new HubspotClient)->actualizarRedes($codigoAgencia, $facebook, $instagram);
    }

    public static function obtenerRedes(int $codigoAgencia){
        return (new HubspotClient)->obtenerRedes($codigoAgencia);
    }
}
