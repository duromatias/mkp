<?php

namespace App\Modules\Users\Resources;

use App\Modules\Auth\Resources\AccesoResource;
use Illuminate\Http\Resources\Json\JsonResource;

class RolResource extends JsonResource
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
			'accesos' => AccesoResource::collection($this->whenLoaded('accesos'))
		];
    }
}
