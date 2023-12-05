<?php

namespace App\Modules\Publicaciones\MisPublicaciones\Commands;

use Illuminate\Console\Command;

class TestMedicionDeTiempos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publicaciones:test-medicion-de-tiempos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test medicion de tiempos';

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
        $medicion = new \App\Base\Debug\MedicionDeTiempos('nombre');
        print_r($medicion);
        sleep(1);
        //$medicion->registrar();
    }
}
