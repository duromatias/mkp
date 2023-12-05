<?php

namespace App\Modules\Vehiculos\Commands;

use App\Modules\Vehiculos\Business\VehiculosBusiness;
use Illuminate\Console\Command;

class TestCacheInfoauto extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vehiculos:test-cache-infoauto';

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
        $rs = VehiculosBusiness::listarMarcas([
            'query_string' => 'peug',
        ]);
        print_r($rs);
        
        $rs = VehiculosBusiness::listarModelosPorMarca(32, [
            'price_at' => 2020,
            'query_string' => '308 1.6 allure'
        ]);
        print_r($rs);
    }
}
