<?php

namespace App\Modules\Subastas\Resources;

use App\Modules\Publicaciones\Subastas\HomeBusiness;
use App\Modules\Subastas\Business\SubastasBusiness;
use Illuminate\Http\Resources\Json\JsonResource;

class SubastaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = auth('api')->user();
        $cantidadPublicaciones = HomeBusiness::obtenerCantidadPublicaciones($user, [
            'subasta_id' => $this->id,
        ]);
        return [
        	'id' => $this->id,
        	'fecha_inicio_inscripcion' => $this->fecha_inicio_inscripcion,
			'fecha_fin_inscripcion' => $this->fecha_fin_inscripcion,
			'fecha_inicio_ofertas' => $this->fecha_inicio_ofertas,
			'fecha_fin_ofertas' => $this->fecha_fin_ofertas,
			'estado' => $this->estado,
			'created_at' => $this->created_at,
            'puede_inscribir' => SubastasBusiness::puedeInscribir($this->resource),
            'puede_ofertar' => SubastasBusiness::puedeOfertar($this->resource),
			'cantidad_publicaciones' => $cantidadPublicaciones
		];
    }
}
