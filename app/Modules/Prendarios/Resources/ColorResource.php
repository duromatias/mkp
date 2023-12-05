<?php

namespace App\Modules\Prendarios\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ColorResource extends JsonResource
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
        	'Codigo' => $this->Codigo,
			'Nombre' => $this->Nombre
		];
    }
}
