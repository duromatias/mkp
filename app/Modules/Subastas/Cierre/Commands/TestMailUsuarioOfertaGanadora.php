<?php

namespace App\Modules\Subastas\Cierre\Commands;

use App\Modules\Publicaciones\Ofertas\Models\Oferta;
use App\Modules\Subastas\Cierre\Emails\UsuarioOfertaGanadora;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestMailUsuarioOfertaGanadora extends Command {

    protected $signature = 'subastas:test-mail-usuario-oferta-ganadora {?--subasta-id}';

    protected $description = 'Test mail usuario de oferta ganadora';

    public function __construct() {
        parent::__construct();
    }

    public function handle() {
        $oferta = Oferta::getById(23);
        Mail::to('scordova@kodear.net')->send(new UsuarioOfertaGanadora($oferta));
        
    }
}
