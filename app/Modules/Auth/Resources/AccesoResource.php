<?php

namespace App\Modules\Auth\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccesoResource extends JsonResource
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
			'descripcion' => $this->descripcion,
			'ruta' => $this->ruta,
			'icono' => $this->icono,
			'orden' => $this->orden,
			'grupo' => $this->grupo
		];
    }
}
