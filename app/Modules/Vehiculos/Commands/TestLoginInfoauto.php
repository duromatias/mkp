<?php

namespace App\Modules\Vehiculos\Commands;

use App\Modules\Vehiculos\Business\VehiculosBusiness;
use Illuminate\Console\Command;

class TestLoginInfoauto extends Command {
    
    protected $signature   = 'vehiculos:test-login-infoauto';
    protected $description = 'Test login de infoauto';

    public function handle() {
        
        //$this->testListarMarcas();
        //$this->testListarModelosPorMarca();
        $this->testGetOneModelo();
    }
    
    private function testListarMarcas() {
        $resp = VehiculosBusiness::listarMarcas([
            'query_string' => 'peu',
        ]);
        
        print_r($resp);
    }
    
    private function testListarModelosPorMarca() {
        $resp = VehiculosBusiness::listarModelosPorMarca(32, []);
        
        //print_r($resp);
    }
    
    private function testGetOneModelo() {
        VehiculosBusiness::getOneModelo(180183);
    }
}
