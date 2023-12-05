<?php

namespace App\Modules\Seguros\Commands;

use App\Modules\Seguros\Client\SegurosFactory;
use Illuminate\Console\Command;

class PruebaListarSeguros extends Command {
    
    protected $signature = 'seguros:prueba-listar-seguros';
    protected $description = 'Prueba de login';

    public function __construct() {
        parent::__construct();
    }
    
    public function handle() {
        $cliente = SegurosFactory::crear();
        $respuesta = $cliente->listarSeguros(
            320640,
            2013,
            2000,
            'ROSARIO',
            'SANTA FE'
        );
        print_r($respuesta);
        
        return 0;
    }
}
