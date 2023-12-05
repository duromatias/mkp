<?php

namespace App\Modules\Auth\Dtos;

use App\Modules\Auth\Requests\RegistrarParticularRequest;
use App\Modules\Shared\Dtos\DataTransferObject;

class RegistrarParticularDto extends DataTransferObject
{
	public string $email;
	public string $password;
	public string $nombre;

	public static function fromRequest(RegistrarParticularRequest $request) {
		return new self($request->validated());
	}
}