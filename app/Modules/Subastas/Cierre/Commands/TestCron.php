<?php

namespace App\Modules\Subastas\Cierre\Commands;

use App\Modules\Email\Email;
use Illuminate\Console\Command;

class TestCron extends Command {

    protected $signature = 'subastas:test-cron {fecha?} {notificar?}';

    protected $description = 'Test cron';

    public function __construct() {
        parent::__construct();
    }

    public function handle() {        
        $fecha = $this->argument('fecha')??$this->ask('Fecha');
        Email::enviar('scordova@kodear.net', 'Test Cron', "Fecha: {$fecha}");
    }
}
