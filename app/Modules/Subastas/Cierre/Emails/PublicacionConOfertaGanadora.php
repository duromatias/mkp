<?php

namespace App\Modules\Subastas\Cierre\Emails;

use App\Modules\Publicaciones\Publicacion;
use App\Modules\Publicaciones\PublicacionesBusiness;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PublicacionConOfertaGanadora extends Mailable {
    
    use Queueable, SerializesModels;

    private Publicacion $publicacion;


    public function __construct(Publicacion $publicacion){
        $this->publicacion = $publicacion;
    }

    public function build() {

        $this->publicacion->load(['ultimaOferta.usuario']);
        return $this->subject('Felicitaciones ¡Tenemos una oferta ganadora!')
			->markdown('publicacion-con-oferta-ganadora')
        	->with([
        		'publicacion'    => $this->publicacion,
                'urlPublicacion' => PublicacionesBusiness::obtenerUrlMarketplace($this->publicacion->id),
			]);
    }
}
