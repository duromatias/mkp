<?php

namespace App\Modules\Auth;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Dtos\RegistrarAgenciaDto;
use App\Modules\Auth\Dtos\RegistrarParticularDto;
use App\Modules\Auth\Requests\CambiarPasswordRequest;
use App\Modules\Auth\Requests\LoginRequest;
use App\Modules\Auth\Requests\RecuperarPasswordRequest;
use App\Modules\Auth\Requests\RegistrarAgenciaRequest;
use App\Modules\Auth\Requests\RegistrarParticularRequest;
use App\Modules\Auth\Requests\ResetPassword;
use App\Modules\Auth\Resources\AccesoResource;
use App\Modules\Auth\Services\AuthService;
use App\Modules\Publicaciones\Consultas\ConsultasBusiness;
use App\Modules\Users\Resources\RolResource;
use App\Modules\Users\Resources\UserResource;
use App\Modules\Parametros\ParametrosApp;
use Illuminate\Http\Request;

class AuthController extends Controller
{
	private AuthService $authService;

	public function __construct(
		AuthService $authService
	) {
		$this->authService = $authService;
	}

	public function me() {
        try {
            $currentUser = $this->getUser();
        } catch (\Exception $ex) {
            $currentUser = null;
        }

		return response()->json([
			'user' => $currentUser ? new UserResource($currentUser) : null,
			'accesos' => AccesoResource::collection($this->authService->getAccesos($currentUser)),
			'consultas_pendientes' => $currentUser ? ConsultasBusiness::contarPendientes($currentUser->id) : 0,
            'parametros' => ParametrosApp::obtenerEnFormatoAsociativo(),
		]);
	}

	public function roles() {
		$roles = $this->authService->getRoles();

		return RolResource::collection($roles);
	}

	public function registrarParticular(RegistrarParticularRequest $request) {
		$registrarParticularDto = RegistrarParticularDto::fromRequest($request);

		$user = $this->authService->registrarParticular($registrarParticularDto);

		return response()->json([
			'user' => new UserResource($user),
			'access_token' => $user->createToken('Bearer Token')->accessToken,
		]);
	}

	public function registrarAgencia(RegistrarAgenciaRequest $request) {
		$registrarAgenciaDto = RegistrarAgenciaDto::fromRequest($request);

		$user = $this->authService->registrarAgencia($registrarAgenciaDto);
        $credenciales = Services\LoginBusiness::generarCredenciales($user);

		return response()->json([
			'user' => new UserResource($user),
			'access_token' => $credenciales['access_token'],
		]);
	}

	public function login(LoginRequest $request) {
		$email = $request->email;
		$password = $request->password;

		$result = $this->authService->login($email, $password);

		return response()->json($result);
	}

	public function recuperarPassword(RecuperarPasswordRequest $request) {
		$email = $request->email;

		$result = $this->authService->recuperarPassword($email);

		return response()->json($result);
	}

	public function resetPassword(ResetPassword $request) {
		$email = $request->email;
		$password = $request->password;
		$token = $request->token;

		$result = $this->authService->resetPassword($email, $password, $token);

		return response()->json($result);
	}

	public function logout() {
		$result = $this->authService->logout();

		return response()->json(['message' => 'Access token revocado']);
	}

	public function cambiarPassword(CambiarPasswordRequest $request) {
		$this->authService->cambiarPassword($request->old_password, $request->new_password);

		return response()->json([
			'status' => 'La contraseña ha sido actualizada'
		]);
	}
    
    public function puedeRegistrarEmailEnOnboarding(Request $request) {
        $email = $request->get('email');
        $request->validate(['email'=>'required|string']);
        return response()->json([
            'data' => AuthService::puedeRegistrarEmailEnOnboarding($email)
        ]);
    }
}
