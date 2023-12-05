<?php

namespace App\Modules\Vehiculos\Commands;

use App\Modules\Vehiculos\Business\VehiculosBusiness;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vehiculos:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $rs = VehiculosBusiness::listarMarcas(['query_string'=>'PEUG']);
        print_r($rs);
        $rs = VehiculosBusiness::listarModelosPorMarca(32, ['query_string'=>'208', 'price_at' => '2021']);
        print_r($rs);
    }
}
