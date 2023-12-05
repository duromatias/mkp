<?php

namespace App\Modules\Onboarding\Auth;

use App\Base\Repository\RepositoryException;
use App\Modules\Auth\Services\LoginBusiness;
use App\Modules\Shared\Clients\OnboardingClient;
use App\Modules\Shared\Exceptions\BusinessException;
use App\Modules\Users\Models\User;
use Laravel\Passport\Token;

class AuthBusiness {
    
    /**
     * @todo: Esto no debería estar aca
     * Debería ser un proceso de autenticación en el modulo App\Modules\Auth\AuthBusiness::loginContokenOnBoarding()
     * ya que es un proceso del sistema, y que en algún punto llegue este Business a realizar alguna parte de la lógica
     * Pero está mal que en este módulo se genere un login para el MarketPlace.
     * 
     * @param string $accessToken
     * @param string $email
     * @return type
     * @throws BusinessException
     */
    static public function loginConTokenOnboarding(string $accessToken, string $email) {
        
        OnboardingClient::$accessToken = $accessToken;
        
        $datosUsuario = static::obtenerDatosUsuario($accessToken);
        
        if ($datosUsuario['email'] !== $email) {
            throw new BusinessException('Las credenciales no coinciden');
        }
        
        try {
			$user = User::getOne([
				'email' => $email
			]);
            
			// Usuario deshabilitado
			if ($user->estado === User::DESHABILITADO) {
				throw new BusinessException('Su usuario no está habilitado para operar');
			}

			// Usuario con rol no autorizado
			if (!$user->esAgencia()) {
				throw new BusinessException('Su usuario no está habilitado para operar. Verificar su rol');
			}
            
        } catch (RepositoryException $e) {
            $user = LoginBusiness::crearUsuarioAgenciaDesdeOnboarding($email, config('users.default_password'));
        }
        
        return LoginBusiness::generarCredenciales($user);
    }
    
    static public function obtenerAccessTokenUsuarioConfigurado(): string {
        
        $client = new OnboardingClient();
        $client->login(config('onboarding.user_email'), config('onboarding.user_password'));

		return $client::$accessToken;
    }
    
    static public function obtenerUsuarioConfigurado(): User {
        return User::getByEmail(config('onboarding.user_email'));
    }
    
    static public function obtenerDatosUsuario(string $accessToken): array {
        $response = static::obtenerCliente()->get('/account/profile');
        if ($response->status() !== 200) {
            throw new BusinessException('Las credenciales no coinciden');
        }
        $data = $response->json('data');
        
        return $data;
    }
    
    static private function obtenerCliente() {
        return app()->make(OnboardingClient::class);
    }
    
    static public function obtenerTokenOnboarding(User $user): string {
        $rs = $user->tokens()->get();
        
        $first = $rs->first();
        
        return $first->oboarding_access_token;
    }
    
    static public function agregarTokenOnboarding(Token $token): Token {
		$token->oboarding_access_token = OnboardingClient::$accessToken;
		$token->save();

		return $token;
    }
}
