<?php

namespace App\Modules\Prendarios;

use App\Modules\Onboarding\Auth\AuthBusiness;
use App\Modules\Prendarios\Clients\PrendariosClient;
use App\Modules\Publicaciones\Publicacion;
use App\Modules\Users\Models\User;

class Factory {
    
    private static ?PrendariosClient $instancia = null;
    
    static public function crearPorPublicacion(Publicacion $publicacion): PrendariosClient {
        return static::crearPorUsuario($publicacion->usuario);
    }
    
    static public function crearPorUsuario(User $user): PrendariosClient {
        $email = $user->email;
        $agencyCode = $user->onboardingUser->business->code;
        //die("{$email}, {$agencyCode}\n");
        return static::crear($email, $agencyCode);
    }
    
    static public function crearPorUsuarioConfigurado(): PrendariosClient {
        $user = AuthBusiness::obtenerUsuarioConfigurado();
        return static::crearPorUsuario($user);
    }
    
    static private function crear(string $email, int $agencyId): PrendariosClient {
        if (static::$instancia === null) {
            $instance = new PrendariosClient();
            $instance->loginConApiKey(config('prendarios.api_key'), $email, $agencyId);

            static::$instancia = $instance;
        }
        
        return static::$instancia;
    }
}