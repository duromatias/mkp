<?php

namespace App\Modules\Agencias\Business;

class AgenciasBusiness
{
	public function validarCuit(string $cuit): bool {
		$formatoValido = preg_match('/^(20|23|24|25|26|27|30|33|34)[0-9]{9}$/', $cuit);

		if (!$formatoValido) {
			return false;
		}

		$digitoVerificador = (int)$cuit[10];
		$tipoAndNumero = substr($cuit, 0, 10);

		$reversedTipoAndNumero = array_reverse(str_split($tipoAndNumero));
		$factors = [2, 3, 4, 5, 6, 7, 2, 3, 4, 5];

		$totalSum = 0;

		for ($i = 0; $i < count($reversedTipoAndNumero); $i++) {
			$totalSum += $reversedTipoAndNumero[$i] * $factors[$i];
		}

		$verificadorCalculado = 11 - ($totalSum % 11);

		if ($verificadorCalculado === 11) {
			$verificadorCalculado = 0;
		}
		else if ($verificadorCalculado === 10) {
			$verificadorCalculado = 1;
		}

		return $verificadorCalculado == $digitoVerificador;
	}
}