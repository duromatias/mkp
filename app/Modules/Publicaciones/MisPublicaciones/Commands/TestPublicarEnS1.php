<?php

namespace App\Modules\Publicaciones\MisPublicaciones\Commands;

use App\Modules\Auth\Services\LoginBusiness;
use App\Modules\Publicaciones\MisPublicaciones\MisPublicacionesBusiness;
use App\Modules\Publicaciones\Publicacion;
use Illuminate\Console\Command;

class TestPublicarEnS1 extends Command {
    
    protected $signature = 'mis-publicaciones:test-publicar-en-s1 {publicacion-id?} {clave?}';

    protected $description = 'Prueba de Publicar en s1';

    public function handle() {
        
        $publicacionId = $this->argument('publicacion-id') ?? $this->ask('Publicacion Id');
        $publicacion   = Publicacion::getById($publicacionId);
        $clave         = $this->argument('clave') ?? $this->ask("Clave de {$publicacion->usuario->email}");
        
        
        $result = app()->make(LoginBusiness::class)->login($publicacion->usuario->email, $clave);
        $user = $result['user'];
        
        $return = MisPublicacionesBusiness::publicarEnS1($publicacion);
        
        print_r($return);
    }
}
