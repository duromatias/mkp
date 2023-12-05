<?php

namespace App\Modules\Subastas\Cierre\Commands;

use App\Modules\Subastas\Cierre\CierreBusiness;
use Illuminate\Console\Command;

class TestNotificarCierre extends Command {

    protected $signature = 'subastas:test-notificar-cierre {fecha?} {notificar?}';

    protected $description = 'Test mail usuario de oferta no ganadora';

    public function __construct() {
        parent::__construct();
    }

    public function handle() {        
        $fecha = $this->argument('fecha')??$this->ask('Fecha');
        if ($this->argument('notificar')!=='notificar') {
            CierreBusiness::$debug = true;
        }
        CierreBusiness::notificarSubastaFinalizadaEnFecha($fecha);
    }
}
