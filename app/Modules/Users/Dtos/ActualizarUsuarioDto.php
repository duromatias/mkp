<?php

namespace App\Modules\Users\Dtos;

use App\Modules\Shared\Dtos\DataTransferObject;

class ActualizarUsuarioDto extends DataTransferObject
{
    public ?int    $id            = null;
	public ?string $nombre        = null;
	public ?string $dni           = null;
	public ?string $telefono      = null;
	public ?string $calle         = null;
	public ?string $numero        = null;
	public ?string $localidad     = null;
	public ?string $provincia     = null;
	public ?string $codigo_postal = null;
	public ?float  $latitud       = null;
	public ?float  $longitud      = null;


	public static function fromRequest($request) {
		return new self($request->validated());
	}
}