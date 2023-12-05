<?php

namespace App\Modules\Vehiculos\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TipoCombustibleResource extends JsonResource
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
			'descripcion' => $this->descripcion
		];
    }
}
