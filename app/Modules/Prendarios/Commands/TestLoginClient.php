<?php

namespace App\Modules\Prendarios\Commands;

use App\Modules\Prendarios\Factory;
use Illuminate\Console\Command;

class TestLoginClient extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prendarios:test-login-client';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $cliente = Factory::crearPorUsuarioConfigurado();
        $return = $cliente->get('/agencias/lite/simulador/getModelPrice', [
            'codia' => 320638,
            'year' => 2017,
        ]);

        print_r($return);
    }
}
