<?php

namespace App\Modules\Auth\Services;

use App\Modules\Auth\Dtos\RegistrarAgenciaDto;
use App\Modules\Auth\Dtos\RegistrarParticularDto;
use App\Modules\Auth\Exceptions\RecuperarPasswordException;
use App\Modules\Shared\Exceptions\BusinessException;
use App\Modules\Shared\Exceptions\OnboardingException;
use App\Modules\Shared\Clients\OnboardingClient;
use App\Modules\Shared\Helpers\MapearOnboardingProvincias;
use App\Modules\Shared\Helpers\TraducirOnboardingErrors;
use App\Modules\Users\Models\Rol;
use App\Modules\Users\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthService
{
	private OnboardingClient $onboardingClient;
	private LoginBusiness  $loginBusiness;

	public function __construct(OnboardingClient $onboardingClient, LoginBusiness $loginBusiness) {
		$this->onboardingClient = $onboardingClient;
		$this->loginBusiness = $loginBusiness;
	}

	public function me() {
		/* @var User $currentUser */
		$currentUser = auth('api')->user();

		if ($currentUser && $currentUser->rol_id === ROL::USUARIO_AGENCIA) {
			$currentUser->load([
				'onboardingUser.userPersonalData',
				'onboardingUser.business'
			]);
		}

		return $currentUser;
	}

	public function getAccesos(?User $user) {
		if ($user) {
			// Accesos Usuario loggeado
			$accesos = $user->rol->accesos;
		} else {
			// Accesos de Usuario sin Login
			$rolUsuarioSinLogin = Rol::find(Rol::USUARIO_SIN_LOGIN);

			$accesos = $rolUsuarioSinLogin->accesos;
		}

		return Arr::sort($accesos, function ($value) {
			return $value['orden'];
		});
	}

	public function getRoles() {
		return Rol::find([ROL::USUARIO_AGENCIA, ROL::USUARIO_PARTICULAR]);
	}

	public function registrarParticular(RegistrarParticularDto $registrarParticularDto) {
		return User::crearParticular($registrarParticularDto);
	}


	public function registrarAgencia(RegistrarAgenciaDto $registrarAgenciaDto) {
		$response = $this->registrarOnboarding($registrarAgenciaDto->email, $registrarAgenciaDto->password);

		// Posibles errores de registro
		if ($response->failed()) {
			$exceptionBody = TraducirOnboardingErrors::traducirErrores($response->json());

			throw new OnboardingException($exceptionBody);
		}

		// Almacenar onboarding_user_id
		$onboardingUser = $response->json('data');
		$registrarAgenciaDto->onboarding_user_id = $onboardingUser['id'];


		// Autenticar para setear access token
		$this->onboardingClient->login($registrarAgenciaDto->email, $registrarAgenciaDto->password);

		// Registrar razón social
		$response = $this->registrarRazonSocial($registrarAgenciaDto);

		if ($response->failed()) {
			$exceptionBody = TraducirOnboardingErrors::traducirErrores($response->json());

			throw new OnboardingException($exceptionBody);
		}

		// Setear marketplace_phone
		$business = $response->json('data.business');
		$this->updateBussinessMarketplacePhone($business['id'], $registrarAgenciaDto->telefono);

		return User::crearAgencia($registrarAgenciaDto);
	}


	private function registrarOnboarding(string $email, string $password) {
		return $this->onboardingClient->post('/auth/register', [
			'email' => $email,
			'password' => $password
		]);
	}

	private function registrarRazonSocial(RegistrarAgenciaDto $registrarAgenciaDto) {
		$response = $this->onboardingClient->get('/provinces', []);
		$provincias = $response->json('data');

		$provinciaId = MapearOnboardingProvincias::getProvinciaId($provincias, $registrarAgenciaDto->provincia);


		return $this->onboardingClient->post('/onboarding/business', [
			"fiscal_condition_id" => "4",
			"type_id" => "1",
			"address_province_id" => $provinciaId,
			"address_locality" => $registrarAgenciaDto->localidad,
			"address_postal_code" => $registrarAgenciaDto->codigo_postal,
			"address_street" => $registrarAgenciaDto->calle,
			"address_number" => $registrarAgenciaDto->numero,
			"latitude" => $registrarAgenciaDto->latitud,
			"longitude" => $registrarAgenciaDto->longitud,
			"cuit" => $registrarAgenciaDto->cuit,
			"name" => $registrarAgenciaDto->razon_social,
			"phone" => $registrarAgenciaDto->telefono,
		]);
	}

	public function updateBussinessMarketplacePhone(int $businessId, string $telefono) {
		return $this->onboardingClient->post("/onboarding/business/{$businessId}", [
			'marketplace_phone' => $telefono
		]);
	}

	public function login(string $email, string $password) {
		return $this->loginBusiness->login($email, $password);
	}


	public function recuperarPassword(string $email) {
		$user = User::getOne(['email' => $email]);

		if (!in_array($user->rol_id, [Rol::ADMINISTRADOR, Rol::USUARIO_PARTICULAR])) {
			throw new RecuperarPasswordException('No es posible cambiar la contreña para el rol del usuario');
		}

		$status = Password::sendResetLink(['email' => $email]);

		return ['status' =>__($status)];
	}

	public function resetPassword(string $email, string $password, string $token) {
		$status = Password::reset(
			['email' => $email, 'password' => $password, 'token' => $token],
			function ($user, $password) {
				$user->password =  Hash::make($password);
				$user->save();

				event(new PasswordReset($user));
			}
		);

		return ['status' => __($status)];
	}


	public function logout() {
		/* @var User $currentUser */
		$currentUser = Auth::user();

		return $currentUser->token()->revoke();
	}


	public function validarOnboardingTelefono(string $telefono): bool {
		return preg_match('/^0[0-9]{2,4} 15[0-9]{5,8}$/', $telefono);
	}

	public function cambiarPassword(string $old_password, string $newPassword) {
		if ($old_password === $newPassword) {
			throw new BusinessException('La nueva contraseña debe ser diferente de la anterior');
		}

		/* @var User $currentUser */
		$currentUser = auth('api')->user();

		if (!in_array($currentUser->rol_id, [Rol::ADMINISTRADOR, Rol::USUARIO_PARTICULAR])) {
			throw new BusinessException('Su usuario no tiene permitido el cambio de contraseña');
		}

		if (!Hash::check($old_password, $currentUser->password)) {
			throw new BusinessException('La contraseña ingresada no es correcta');
		}

		$currentUser->password = Hash::make($newPassword);
		$currentUser->save();
	}
    
    static public function puedeRegistrarEmailEnMarketplace(string $email): bool {
        return User::contar([
            'email' => $email
        ]) === 0;
    }
    
    static public function puedeRegistrarEmailEnOnboarding(string $email): bool {
        $response = OnboardingClient::make()->get('/unavailable_emails', [
			'email' => $email
		]);

		return !$response->json();
    }
}