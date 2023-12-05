<?php

namespace App\Modules\Prendarios\Commands;

use App\Modules\Auth\Services\LoginBusiness;
use App\Modules\Prendarios\Vehiculos\VehiculosBusiness;
use Illuminate\Console\Command;

class TestLoginYCreacionDeVehiculo extends Command {

    protected $signature = 'prendarios:test-login-y-creacion-vehiculo';

    protected $description = 'Con un usuario onboarding, crea un vehículo en el sistema prendarios (S1)';

    public function handle() {
        
        $email = $this->ask('Email');
        $clave = $this->ask('Clave');
        
        $result = app()->make(LoginBusiness::class)->login($email, $clave);
        $user = $result['user'];

        
        VehiculosBusiness::crear($user, 320693, 2017, 45000, 1346000, [
            '/home/kodear/Documents/kodear/decreditos/fotos_vehiculos/peugeot_308_mio/20160202_171521.jpg',
            '/home/kodear/Documents/kodear/decreditos/fotos_vehiculos/peugeot_308_mio/20161030_085724.jpg',
            '/home/kodear/Documents/kodear/decreditos/fotos_vehiculos/peugeot_308_mio/20180311_140152.jpg',
            '/home/kodear/Documents/kodear/decreditos/fotos_vehiculos/peugeot_308_mio/20200125_095712.jpg',
            '/home/kodear/Documents/kodear/decreditos/fotos_vehiculos/peugeot_308_mio/20210322_172734.jpg',
        ]);
    }
}
