<?php

namespace App\Modules\Hubspot\Commands;

use App\Modules\Hubspot\HubspotBusiness;use Illuminate\Console\Command;

class PruebaObtenerRedes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hubspot:prueba-obtener-redes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prueba obtener redes sociales';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        HubspotBusiness::obtenerRedes(40416066);
        return 0;
    }
}
