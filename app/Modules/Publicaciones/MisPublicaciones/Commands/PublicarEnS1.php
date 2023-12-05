<?php

namespace App\Modules\Publicaciones\MisPublicaciones\Commands;

use App\Modules\Publicaciones\MisPublicaciones\MisPublicacionesBusiness;
use App\Modules\Publicaciones\Publicacion;
use Illuminate\Console\Command;

class PublicarEnS1 extends Command {

    protected $signature = 'publicaciones:publicar-en-s1';

    protected $description = 'Copia una publicación en S1';

    public function handle() {
        $publicacionId = $this->ask('Publicacion #');
        $publicacion = Publicacion::getById($publicacionId);
        MisPublicacionesBusiness::publicarEnS1($publicacion);
    }
}
