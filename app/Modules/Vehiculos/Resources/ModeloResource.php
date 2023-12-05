<?php

namespace App\Modules\Vehiculos\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ModeloResource extends JsonResource
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
			'brand'           => $this->brand,
            'codia'           => $this->codia,
            'description'     => $this->description,
            'photo_url'       => $this->photo_url,
            'prices_form'     => $this->prices_from,
            'prices_to'       => $this->prices_to,
            'precio_sugerido' => $this->precio_sugerido ?? null,
		];
    }
}
