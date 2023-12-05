<?php

namespace App\Modules\Publicaciones\Favoritos\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FavoritoResource extends JsonResource
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
			'publicacion_id' => $this->publicacion_id,
			'usuario_id' => $this->usuario_id
		];
    }
}
