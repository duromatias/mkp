<?php

namespace App\Modules\Subastas\Cierre\Commands;

use App\Modules\Publicaciones\Publicacion;
use App\Modules\Subastas\Cierre\Emails\PublicacionSinOfertaGanadora;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestMailPublicacionSinOfertaGanadora extends Command {

    protected $signature = 'subastas:test-mail-pulicacion-sin-oferta-ganadora {?--subasta-id}';

    protected $description = 'Test mail publicacion sin oferta ganadora';

    public function __construct() {
        parent::__construct();
    }

    public function handle() {
        $publicacion = Publicacion::getById(159);
        Mail::to('scordova@kodear.net')->send(new PublicacionSinOfertaGanadora($publicacion));
        
    }
}
