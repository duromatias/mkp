<?php

namespace App\Modules\Subastas\Dtos;

use App\Modules\Subastas\Requests\UpdateSubastaRequest;

class UpdateSubastaDto extends \App\Modules\Shared\Dtos\DataTransferObject
{
	public string $fecha_inicio_inscripcion;
	public string $fecha_fin_inscripcion;
	public string $fecha_inicio_ofertas;
	public string $fecha_fin_ofertas;

	public static function fromRequest(UpdateSubastaRequest $request) {
		return new self($request->validated());
	}
}