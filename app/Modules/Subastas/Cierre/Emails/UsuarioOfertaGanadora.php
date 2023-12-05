<?php

namespace App\Modules\Subastas\Cierre\Emails;

use App\Modules\Publicaciones\Ofertas\Models\Oferta;
use App\Modules\Publicaciones\PublicacionesBusiness;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UsuarioOfertaGanadora extends Mailable {
    
    use Queueable, SerializesModels;

    private Oferta $oferta;


    public function __construct(Oferta $oferta){
        $this->oferta = $oferta;
    }

    public function build() {

        $this->oferta->load(['publicacion.usuario']);
        $publicacion = $this->oferta->publicacion;
        
        return $this->subject('¡Tu oferta ha sido la superadora!')
			->markdown('usuario-oferta-ganadora')
        	->with([
                'moneda'           => $publicacion->moneda,
                'precio'           => number_format($this->oferta->precio_ofertado, 2, ',', '.'),
                'nombreVehiculo'   => $publicacion->obtenerNombreVehiculo(),
                'vendedorNombre'   => $publicacion->usuario->obtenerNombreVendedor(),
                'vendedorTelefono' => $publicacion->usuario->obtenerTelefonoContacto(),
                'vendedorEmail'    => $publicacion->usuario->email,
                'urlPublicacion'   => PublicacionesBusiness::obtenerUrlMarketplace($publicacion->id),
        ]);
    }
}
