<?php

namespace App\Modules\Vehiculos;

use Kodear\Laravel\InfoAutoClient\InfoAutoCarService;

class InfoAutoCarClient extends InfoAutoCarService {
    
    static public function make(): self {
        return app()->make(static::class);
    }
}
