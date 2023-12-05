<?php

namespace App\Modules\Subastas\Dtos;

use App\Modules\Shared\Dtos\DataTransferObject;
use App\Modules\Subastas\Requests\CreateSubastaRequest;
use DateTime;

class CreateSubastaDto extends DataTransferObject
{
	public string $fecha_inicio_inscripcion;
	public string $fecha_fin_inscripcion;
	public string $fecha_inicio_ofertas;
	public string $fecha_fin_ofertas;

	public static function fromRequest(CreateSubastaRequest $request) {
		return new self($request->validated());
	}
}