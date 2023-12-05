<?php

namespace App\Modules\Users\Business;

use App\Modules\Auth\Services\LoginBusiness;
use App\Modules\Onboarding\Models\OnboardingUser;
use App\Modules\Shared\Exceptions\BusinessException;
use App\Base\Repository\RepositoryException;
use App\Modules\Users\Dtos\ActualizarUsuarioDto;
use App\Modules\Users\Emails\SolicitudBajaMail;
use App\Modules\Users\Models\User;
use App\Modules\Users\Models\Rol;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserBusiness
{
	static public function getById($id) {
        
		$user = User::getById($id);

        //@todo: revisar, esto no habría que hacerlo más.
		if ($user->rol_id === ROL::USUARIO_AGENCIA) {
			$user->load([
				'onboardingUser.userPersonalData',
				'onboardingUser.business',
				'onboardingUser.address.province'
			]);
		}

		return $user;
	}
    
    static public function actualizarPassword(int $usuarioId, string $passwordActual, string $passwordNueva): void {
        $user = User::getById($usuarioId);
        
        if (!LoginBusiness::verificarPassword($user, $passwordActual)) {
            throw new BusinessException('Contraseña actual incorrecta', [
                'password_actual' => [ 'Contraseña incorrecta' ],
            ]);
        }
        
        $user->actualizarPassword($passwordNueva);
    }

	public function actualizarUsuario(ActualizarUsuarioDto $dto) {
        $usuario = User::getById($dto->id);
        
		if (!in_array($usuario->rol_id, [Rol::ADMINISTRADOR, Rol::USUARIO_PARTICULAR])) {
			throw new BusinessException('Su rol no tiene permitida la edición de datos personales');
		}

        $usuario->actualizarDatosPersonales($dto);

		return $usuario;
	}

	/**
	 * @param int $userId
	 * @param $rolId
	 * @return \App\Base\Repository\ModelRepository|User
	 * @throws \App\Base\Repository\RepositoryException
	 */
	public function actualizarRol(int $userId, int $rolId) {
		$user = User::getById($userId);

		if ($user->rol_id === $rolId) {
			return $user;
		}

		if ($rolId === Rol::USUARIO_AGENCIA) {
			// Validar que exista el email en la tabla de onboarding
			try {
				OnboardingUser::getOne(['email' => $user->email]);
			}
			catch (RepositoryException $exception) {
				throw new BusinessException('El usuario no se encuentra registrado en onboarding');
			}

			// Remover passwrod al transicionar al rol agencia
			$user->password = null;
		}

		if ($user->rol_id === Rol::USUARIO_AGENCIA) {
			// Asignar contraseña temporal debido a que los usuarios de agencia no presentan una al momento de registrarse
			$defaultPassword = config('users.default_password');
			$user->password = Hash::make($defaultPassword);
		}

		$user->rol_id = $rolId;
		$user->guardar();

		return $user;
	}

	public function habilitar(int $userId) {
		$user = User::getById($userId);
        return $user->habilitar();
	}

	public function deshabilitar(int $userId) {
		$user = User::getById($userId);

        return $user->deshabilitar();
	}
    
    static public function listarAdministradores() {
        return User::listarTodos([
            'rol_id' => Rol::ADMINISTRADOR,
            'estado' => User::HABILITADO,
        ]);
    }

	public static function solicitarBaja(User $user) {
		$admins = static::listarAdministradores();

		foreach ($admins as $admin) {
			Mail::to($admin->email)->send(new SolicitudBajaMail($user->email));
		}
	}
}