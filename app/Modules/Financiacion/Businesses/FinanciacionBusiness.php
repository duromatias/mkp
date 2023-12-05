<?php

namespace App\Modules\Financiacion\Businesses;

use App\Base\BusinessException;
use App\Modules\Onboarding\Auth\AuthBusiness;
use App\Modules\Parametros\Parametro;
use App\Modules\Prendarios\Clients\PrendariosClient;
use App\Modules\Publicaciones\Publicacion;
use App\Modules\Users\Models\User;
use Carbon\Carbon;

class FinanciacionBusiness
{
	static public function validarPuedeFinanciarVehiculo(string $fullName, int $year): void {
		// Verificar financiación con Prendarios
		$url = '/agencias/lite/simulador/getModelsByYear';

		$response = (new PrendariosClient())->get($url, [
			'query' =>  $fullName,
			'year' => $year,
			'access_token' => AuthBusiness::obtenerAccessTokenUsuarioConfigurado()
		]);

        $labels = array_column($response, 'label');

        $coincidencias = array_filter($labels, function ($label) use ($fullName) {
            return $label === $fullName;
        });

        $antiguedadMaxima = Parametro::obtenerAntiguedadMaximaFinanciacion();
        $anioActual = Carbon::now()->year;

		if (count($coincidencias) !== 1 || ($anioActual - $antiguedadMaxima) > $year) {
			throw new BusinessException('El modelo no se encuentra habilitado para financiación');
		}
	}

    static public function validarUsuarioParaFinanciacion(User $user): void {
		if (!$user->esAgencia()) {
			throw new BusinessException('Rol no autorizado a crear una publicación con financiación');
		}

		if (!$user->esAgenciaReady()) {
			throw new BusinessException('Debe completar sus datos en la plataforma de Onboarding antes de poder asignar financiación a la publicación');
		}
    }

	static public function verificar(string $fullName, int $year): bool {
		try {
			static::validarPuedeFinanciarVehiculo($fullName, $year);

			return true;
		} catch (\Exception $exception) {
			return false;
        }
    }

    static public function sincronizarFinanciacion(Publicacion $publicacion, bool $financiacion): void {
        if ((bool)$publicacion->financiacion !== $financiacion) {
            if ($financiacion) {
                static::marcarConFinanciacion($publicacion);
            } else {
                static::marcarSinFinanciacion($publicacion);
            }
        }
    }

    static public function marcarConFinanciacion(Publicacion $publicacion): void {
        static::validarUsuarioParaFinanciacion($publicacion->usuario);
        static::validarPuedeFinanciar($publicacion);

        $publicacion->financiacion = 1;
        $publicacion->guardar();
    }

    static public function marcarSinFinanciacion(Publicacion $publicacion): void {
        static::validarUsuarioParaFinanciacion($publicacion->usuario);
        $publicacion->financiacion = 0;
        $publicacion->guardar();
    }

    static public function validarPuedeFinanciar(Publicacion $publicacion): void {
        if ($publicacion->subasta_id !== null) {
            throw new BusinessException('Los vehículos que se incluyen en subastas no pueden ser financiados');
        }

        $fullName = "{$publicacion->marca} {$publicacion->modelo}";
        static::validarPuedeFinanciarVehiculo($fullName, $publicacion->año);
    }
}
