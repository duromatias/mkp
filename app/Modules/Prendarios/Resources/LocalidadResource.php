<?php

namespace App\Modules\Prendarios\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LocalidadResource extends JsonResource
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
			'id'        => $this->ID, // en mayúsculas
			'localidad' => $this->localidad,
			'provincia' => $this->provincia,
			'localidad_provincia' => "{$this->localidad} - {$this->provincia}",
			'codigo_postal_localidad_provincia' => "{$this->codpos} - {$this->localidad} - {$this->provincia}",
			'codpost' => $this->codpos,
		];
    }
}
