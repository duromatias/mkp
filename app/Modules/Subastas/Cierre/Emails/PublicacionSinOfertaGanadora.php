<?php

namespace App\Modules\Subastas\Cierre\Emails;

use App\Modules\Publicaciones\Publicacion;
use App\Modules\Publicaciones\PublicacionesBusiness;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PublicacionSinOfertaGanadora extends Mailable {
    
    use Queueable, SerializesModels;

    private Publicacion $publicacion;


    public function __construct(Publicacion $publicacion){
        $this->publicacion = $publicacion;
    }

    public function build() {
        $vehiculo = $this->publicacion->obtenerNombreVehiculo();
        return $this->subject("Tu subasta de {$vehiculo} no alcanzó su objetivo")
			->markdown('publicacion-sin-oferta-ganadora')
        	->with([
        		'publicacion'    => $this->publicacion,
                'urlPublicacion' => PublicacionesBusiness::obtenerUrlMarketplace($this->publicacion->id),
			]);
    }
}
