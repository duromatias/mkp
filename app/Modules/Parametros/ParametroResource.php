<?php

namespace App\Modules\Parametros;

use Illuminate\Http\Resources\Json\JsonResource;

class ParametroResource extends JsonResource
{
	public function toArray($request) {
		return [
			'id' => $this->id,
			'descripcion' => $this->descripcion,
			'valor' => $this->valor,
		];
	}
}