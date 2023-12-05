<?php

namespace App\Modules\Publicaciones\Ofertas\Dtos;

use App\Modules\Publicaciones\Ofertas\Requests\CreateOfertaRequest;
use App\Modules\Shared\Dtos\DataTransferObject;

class CreateOfertaDto extends DataTransferObject
{
	public int $precio_ofertado;
	public int $usuario_id;
	public int $publicacion_id;

	public static function fromRequest(CreateOfertaRequest $request): self {
		return new self($request->validated());
	}
}