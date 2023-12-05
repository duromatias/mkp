<?php

namespace App\Modules\Publicaciones\MisPublicaciones\Commands;

use App\Modules\Publicaciones\MisPublicaciones\MisPublicacionesBusiness;
use Illuminate\Console\Command;

class SendEmailPublicacionesProximaVencer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:publicaciones-proximas-vencer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enviar email a los usuarios cuya publicación se encuentra próxima a vencer';

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
		MisPublicacionesBusiness::notificarPublicacionesProximasVencer();
    }
}
