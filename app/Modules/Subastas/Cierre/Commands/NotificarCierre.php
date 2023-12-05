<?php

namespace App\Modules\Subastas\Cierre\Commands;

use App\Modules\Subastas\Cierre\CierreBusiness;
use Illuminate\Console\Command;

class NotificarCierre extends Command {

    protected $signature = 'subastas:notificar-cierre {fecha?}';

    protected $description = 'Notifica el resultado de la subasta finalizada a los usuarios participantes';

    public function __construct() {
        parent::__construct();
    }

    public function handle() {
        CierreBusiness::notificarSubastaFinalizadaEnFecha($this->argument('fecha'));
    }
}
