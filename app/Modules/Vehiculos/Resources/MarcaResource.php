<?php

namespace App\Modules\Vehiculos\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MarcaResource extends JsonResource
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
			'id'          => $this->id,
            'logo_url'    => $this->logo_url,
            'name'        => $this->name,
            'prices_from' => $this->prices_from,
            'prices_to'   => $this->prices_to,
        ];
    }
}
