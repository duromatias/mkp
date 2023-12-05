<?php

namespace App\Modules\Publicaciones\MisPublicaciones;

class ActualizarDto extends BaseDto {
    
    public static function fromRequest(ActualizarRequest $request) {
		return new self($request->validated());
	}
}