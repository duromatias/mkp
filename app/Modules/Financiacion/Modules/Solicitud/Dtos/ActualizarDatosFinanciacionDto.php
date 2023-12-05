<?php

namespace App\Modules\Financiacion\Modules\Solicitud\Dtos;

use App\Base\DataTransferObject;

class ActualizarDatosFinanciacionDto extends DataTransferObject
{
	public string $nombre;
	public string $apellido;
	public string $dni;
	public string $codigo_postal;
    public string $localidad;
    public string $provincia;
	public string $sexo;
	public string $telefono;
	public string $estado_civil_id;
	public string $uso_vehiculo;
}