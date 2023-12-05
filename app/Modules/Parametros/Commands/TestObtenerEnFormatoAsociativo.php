<?php

namespace App\Modules\Parametros\Commands;

use App\Modules\Parametros\Parametro;
use App\Modules\Parametros\ParametrosApp;
use Illuminate\Console\Command;

class TestObtenerEnFormatoAsociativo extends Command {
    
    protected $signature = 'parametros:test-obtener-en-formato-asociativo';
    protected $description = 'Command description';
    
    public function handle(): int {
        
        ParametrosApp::registrar(function() {
            $parametro = new Parametro;
            
            $parametro->id = null;
            $parametro->nombre = 'prueba';
            $parametro->descripcion = 'desc';
            $parametro->valor = 'prueba';
            
            return $parametro;
        });
        
        $parametros = ParametrosApp::obtenerEnFormatoAsociativo();
        print_r($parametros);
        return 0;
    }
}
