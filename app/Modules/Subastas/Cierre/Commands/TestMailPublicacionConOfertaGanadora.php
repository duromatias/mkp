<?php

namespace App\Modules\Subastas\Cierre\Commands;

use App\Modules\Publicaciones\Publicacion;
use App\Modules\Subastas\Cierre\Emails\PublicacionConOfertaGanadora;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestMailPublicacionConOfertaGanadora extends Command {

    protected $signature = 'subastas:test-mail-pulicacion-con-oferta-ganadora {?--subasta-id}';

    protected $description = 'Test mail publicacion con oferta ganadora';

    public function __construct() {
        parent::__construct();
    }

    public function handle() {
        $publicacion = Publicacion::getById(159);
        Mail::to('scordova@kodear.net')->send(new PublicacionConOfertaGanadora($publicacion));
        
    }
}
