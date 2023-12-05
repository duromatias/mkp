<?php

namespace App\Modules\Publicaciones\Multimedia;

use Illuminate\Http\Resources\Json\JsonResource;

class MultimediaResource extends JsonResource {

    public function toArray($request) {
        
        $url     = MultimediaBusiness::getUrlImagen($this->id, $this->extension??'');
        $urlCard = MultimediaBusiness::getUrlImagen($this->id, $this->extension??'', 'card');
        
        return [
        	'id'         => $this->id,
            'es_portada' => $this->es_portada,
            'tipo'       => $this->tipo,
            'url'        => $url,
            'urlCard'    => $urlCard ?? $url,
        ];
    }
}
