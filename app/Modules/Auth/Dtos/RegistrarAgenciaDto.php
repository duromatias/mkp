<?php

namespace App\Modules\Auth\Dtos;

use App\Modules\Auth\Requests\RegistrarAgenciaRequest;
use App\Modules\Shared\Dtos\DataTransferObject;

class RegistrarAgenciaDto extends DataTransferObject
{
	public string $email;
	public string $password;
	public string $cuit;
	public string $razon_social;
	public string $calle;
	public int $numero;
	public string $localidad;
	public string $provincia;
	public string $codigo_postal;
	public float $latitud;
	public float $longitud;
	public string $telefono;
	public int $onboarding_user_id;



	public static function fromRequest(RegistrarAgenciaRequest $request) {
		return new self($request->validated());
	}
}