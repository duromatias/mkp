<?php

namespace App\Modules\Seguros\Commands;

use App\Modules\Seguros\Client\SegurosFactory;
use Illuminate\Console\Command;

class PruebaClientLogin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seguros:prueba-client-login';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prueba de login';

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
        $client = SegurosFactory::crear();
        print_r($client);
        return 0;
    }
}
