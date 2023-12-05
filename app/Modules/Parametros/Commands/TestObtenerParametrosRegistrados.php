<?php

namespace App\Modules\Parametros\Commands;

use App\Modules\Parametros\Parametro;
use App\Modules\Parametros\ParametrosApp;
use Illuminate\Console\Command;

class TestObtenerParametrosRegistrados extends Command {
    
    protected $signature = 'parametros:test-obtener-parametros-registrados';
    protected $description = 'Command description';
    
    public function handle(): int {
        
        $parametros = ParametrosApp::obtenerParametrosRegistrados();
        $listado = array_map(function(Parametro $parametro) {
            return $parametro->toArray();
        }, $parametros);
        $this->table([
            'id', 'descripcion','valor','nombre'
        ], $listado);
        return 0;
    }
}
