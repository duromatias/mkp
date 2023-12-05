<?php

namespace App\Modules\Prendarios\Commands;

use App\Modules\Vehiculos\Business\VehiculosBusiness;
use App\Modules\Vehiculos\InfoAutoCarClient;
use Illuminate\Console\Command;

class TestInfoauto extends Command {

    protected $signature = 'prendarios:test-infoauto';

    protected $description = 'Con un usuario onboarding, crea un vehículo en el sistema prendarios (S1)';

    public function handle() {
        
        // $data = VehiculosBusiness::getOneModelo(120567);
        //print_r($data);
        
        $data = InfoAutoCarClient::make()->get("brands/32/groups");
        print_r($data);
        
        //foreach($data as $row) {
        //    echo "{$row->description} {$row->category_name}\n";
        //}
    }
}
