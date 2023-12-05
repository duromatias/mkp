<?php

namespace App\Modules\Publicaciones\Ofertas\Resources;

use App\Modules\Users\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OfertaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
        	'id' => $this->id,
			'precio_ofertado' => (int) $this->precio_ofertado,
			'created_at' => $this->created_at,
            'usuario_id' => $this->usuario_id,
            'usuario'    => new UserResource($this->whenLoaded('usuario'))
            ];
    }
}
