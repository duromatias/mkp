<?php

namespace App\Modules\Subastas\Cierre\Emails;

use App\Modules\Publicaciones\Publicacion;
use App\Modules\Publicaciones\PublicacionesBusiness;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UsuarioOfertaNoGanadora extends Mailable {
    
    use Queueable, SerializesModels;

    private Publicacion $publicacion;

    public function __construct(Publicacion $publicacion){
        $this->publicacion = $publicacion;
    }

    public function build() {
        
        return $this->subject("Finalizó la subasta de {$this->publicacion->obtenerNombreVehiculo()}")
			->markdown('usuario-oferta-no-ganadora')
        	->with([
                'nombreVehiculo'   => $this->publicacion->obtenerNombreVehiculo(),
                'urlPublicacion'   => PublicacionesBusiness::obtenerUrlMarketplace($this->publicacion->id),
        ]);
    }
}
