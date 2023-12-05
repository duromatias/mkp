<?php

namespace App\Modules\Shared\Helpers;

class MapearOnboardingProvincias
{
	/* Obtener id de la provincia a través del nombre */
	public static function getProvinciaId(array $provincias, string $provinciaTargetName) {
		foreach ($provincias as $provincia) {
			if ( strtoupper($provincia['name']) === strtoupper($provinciaTargetName) ) {
				return $provincia['id'];
			}
		}

		return null;
	}
}