<?php

namespace App\Modules\Subastas\Business;

use App\Base\BusinessException;
use App\Base\Repository\RepositoryException;
use App\Modules\Subastas\Dtos\CreateSubastaDto;
use App\Modules\Subastas\Dtos\UpdateSubastaDto;
use App\Modules\Subastas\Emails\InicioInscripcionMail;
use App\Modules\Subastas\Emails\InicioOfertasMail;
use App\Modules\Subastas\Subasta;
use App\Modules\Users\Models\Rol;
use App\Modules\Users\Models\User;
use Illuminate\Support\Facades\Mail;

class SubastasBusiness
{
	public static function create(CreateSubastaDto $createSubastaDto): Subasta {
		return Subasta::create($createSubastaDto);
	}

	public static function update(int $id, UpdateSubastaDto $updateSubastaDto): Subasta {
		$subasta = Subasta::getById($id);

		if ($subasta->estado !== Subasta::ESTADO_CREADA) {
			throw new BusinessException('No es posible actualizar la subasta debido a que no presenta el estado creada');
		}

		if (!$subasta->sinOfertas()) {
			throw new BusinessException('No es posible actualizar la subasta porque contiene publicaciones con ofertas');
		}

		return $subasta->update((array) $updateSubastaDto);
	}

	public static function cancelar(int $id): Subasta {
		$subasta = Subasta::getById($id);

		if (!$subasta->sinOfertas()) {
			throw new BusinessException('No es posible cancelar la subasta porque contiene publicaciones con ofertas');
		}

		return $subasta->update([
			'estado' => Subasta::ESTADO_CANCELADA
		]);
	}
    
    public static function obtenerDisponible(bool $usarCache = true) {
        static $subasta;
        if (!empty($subasta) && $usarCache) {
            return $subasta;
        }
        
		try {
			$subasta = Subasta::getOne([
				'fecha_disponible' => date('Y-m-d'),
				'estado' => Subasta::ESTADO_CREADA,
			]);

			return $subasta;
		} catch (RepositoryException $exception) {
			return null;
		}
    }
    
    public static function puedeInscribir(Subasta $subasta): bool {
        $fecha = date('Y-m-d');
        return 
            $subasta->fecha_inicio_inscripcion <= $fecha &&
            $subasta->fecha_fin_inscripcion >= $fecha;
    }
    
    public static function puedeOfertar(Subasta $subasta): bool {
        $fecha = date('Y-m-d');
        return 
            $subasta->fecha_inicio_ofertas <= $fecha &&
            $subasta->fecha_fin_ofertas >= $fecha;
    }

	/**
	 * Notificar a los usuarios agencia habilitados acerca de la apertura de inscripción de una subasta
	 */
	public static function notificarInicioInscripcion(Subasta $subasta) {
		$users = User::listar(
			1,
			0,
			[
				'rol_id' => ROL::USUARIO_AGENCIA,
				'estado' => USER::HABILITADO],
			[],
			[
				'onboardingUser.userPersonalData',
				'onboardingUser.business'
			]
		);

		foreach ($users as $user) {
			if ($user->onboardingUser && $user->onboardingUser->userPersonalData && $user->onboardingUser->userPersonalData->first_name) {
				$nombre = $user->onboardingUser->userPersonalData->first_name;
			} else if ($user->onboardingUser && $user->onboardingUser->business && $user->onboardingUser->business->name) {
				$nombre = $user->onboardingUser->business->name;
			} else {
				$nombre = '';
			}

			Mail::to($user->email)->send(new InicioInscripcionMail($subasta, $nombre));
		}
	}


	/**
	 * Notificar a los usuarios agencia habilitados acerca de la apertura de ofertas de una subasta
	 */
    public static function notificarInicioOfertas() {
		$users = User::listar(
			1,
			0,
			[
				'rol_id' => ROL::USUARIO_AGENCIA,
				'estado' => USER::HABILITADO],
			[],
			[
				'onboardingUser.userPersonalData',
				'onboardingUser.business'
			]
		);

		foreach ($users as $user) {
			if ($user->onboardingUser && $user->onboardingUser->userPersonalData && $user->onboardingUser->userPersonalData->first_name) {
				$nombre = $user->onboardingUser->userPersonalData->first_name;
			} else if ($user->onboardingUser && $user->onboardingUser->business && $user->onboardingUser->business->name) {
				$nombre = $user->onboardingUser->business->name;
			} else {
				$nombre = '';
			}

			Mail::to($user->email)->send(new InicioOfertasMail($nombre));
		}
	}
}