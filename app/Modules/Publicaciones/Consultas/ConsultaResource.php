<?php

namespace App\Modules\Publicaciones\Consultas;

use App\Modules\Publicaciones\PublicacionResource;
use App\Modules\Users\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ConsultaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
    	return array_merge(
			[
				'id' => $this->id,
				'publicacion_id' => $this->publicacion_id,
				'usuario_origen_id' => $this->usuario_origen_id,
				'usuario_destino_id' => $this->usuario_destino_id,
				'nombre' => $this->nombre,
                'email'  => $this->email,
				'telefono' => $this->telefono,
				'texto' => $this->texto,
				'respuesta' => $this->respuesta,
				'apto_credito' => $this->apto_credito,
				'estado' => $this->estado,
				'created_at' => $this->created_at,

				'publicacion' => new PublicacionResource($this->whenLoaded('publicacion')),
				'usuario_origen' => new UserResource($this->whenLoaded('usuarioOrigen')),
				'usuario_destino' => new UserResource($this->whenLoaded('usuarioDestino')),
			]
		);
    }
}
