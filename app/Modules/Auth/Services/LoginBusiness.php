<?php

namespace App\Modules\Auth\Services;

use App\Base\Repository\RepositoryException;
use App\Modules\Auth\Dtos\RegistrarAgenciaDto;
use App\Modules\Onboarding\Auth\AuthBusiness;
use App\Modules\Onboarding\Exceptions\DatosUsuarioLivianoIncompletosException;
use App\Modules\Onboarding\Models\OnboardingUser;
use App\Modules\Shared\Clients\OnboardingClient;
use App\Modules\Shared\Exceptions\BusinessException;
use App\Modules\Shared\Exceptions\OnboardingException;
use App\Modules\Shared\Helpers\TraducirOnboardingErrors;
use App\Modules\Users\Models\Rol;
use App\Modules\Users\Models\User;
use App\Modules\Users\Resources\UserResource;
use Illuminate\Support\Facades\Hash;

class LoginBusiness
{
	private OnboardingClient $onboardingClient;

	public function __construct(OnboardingClient $onboardingClient) {
		$this->onboardingClient = $onboardingClient;
	}

	public function login(string $email, string $password) {
		try {
			$user = User::getOne([
				'email' => $email
			]);

			// Usuario deshabilitado
			if ($user->estado === User::DESHABILITADO) {
				throw new BusinessException('Su usuario no está habilitado para operar');
			}

			// Usuario con rol no autorizado
			if (!in_array($user->rol_id, [Rol::ADMINISTRADOR, Rol::USUARIO_AGENCIA, Rol::USUARIO_PARTICULAR])) {
				throw new BusinessException('Su usuario no está habilitado para operar. Verificar su rol');
			}

			// Chequear contraseña únicamente para roles administrador y particular
			if (in_array($user->rol_id, [Rol::ADMINISTRADOR, Rol::USUARIO_PARTICULAR])) {
                if (!static::verificarPassword($user, $password)) {
                    throw new BusinessException('Usuario y/o contraseña inválidos');
                }
			}
		} // Login Onboarding
		catch (RepositoryException $exception) {
			$this->loginOnboarding($email, $password);
            $user = static::crearUsuarioAgenciaDesdeOnboarding($email, $password);
		}

		if ($user->rol_id === Rol::USUARIO_AGENCIA) {
			// Setear access token si no se logeo anteriormente en el catch
			if (!OnboardingClient::$accessToken) {
				$this->loginOnboarding($email, $password);
			}

			$response = $this->onboardingClient->get('/account/profile');

			// Variable de calculada de onboarding
			$ready = $response->json('data.ready');

			if (!$ready) {
				$this->chequearCompletitudDatosUsuarioLiviano($user);
			}
		}

		return static::generarCredenciales($user);
	}
    
    static public function crearUsuarioAgenciaDesdeOnboarding(string $email, string $password): User {
        $onboardingUser = OnboardingUser::getOne([
            'email' => $email
        ]);

        $datos['onboarding_user_id'] = $onboardingUser->id;
        $datos['email'] = $onboardingUser->email;
        $datos['password'] = $password;

        $userAgenciaDto = RegistrarAgenciaDto::fromArray($datos);

        $user = User::crearAgencia($userAgenciaDto);

        return $user;
    }
    
    static public function generarCredenciales(User $user): array {
        
        if ($user->esAgencia()) {
            $user->load(['onboardingUser.userPersonalData', 'onboardingUser.business']);
        }
        
        $user->load('rol');
        $tokenResult = $user->createToken('Bearer Token');
        $token = AuthBusiness::agregarTokenOnboarding($tokenResult->token);

		return [
			'user' => $user,
			'access_token' => $tokenResult->accessToken,
			'onboarding_access_token' => $token->oboarding_access_token
		];
    }

	public function loginOnboarding(string $email, string $password) {
		$response = $this->onboardingClient->login($email, $password);

		if ($response->failed()) {
			$exceptionBody = TraducirOnboardingErrors::traducirErrores($response->json());

			throw new OnboardingException($exceptionBody);
		}
	}

	// Chequea que el usuario presente la totalidad de datos de usuario liviano cargados
	public function chequearCompletitudDatosUsuarioLiviano(User $user) {
		$business = $user->onboardingUser->business_id ?  $user->onboardingUser->business : null;

		if ($business === null || in_array(null, [
				$business,
				$business->cuit,
				$business->name,
				$business->phone,
				$business->address->street,
				$business->address->number,
				$business->address->locality,
				$business->address->province_id
			])) {
			throw new DatosUsuarioLivianoIncompletosException('Datos incompletos');
		}
	}
    
    static public function verificarPassword(User $user, string $password): bool {
        return Hash::check($password, $user->password);
    }
}