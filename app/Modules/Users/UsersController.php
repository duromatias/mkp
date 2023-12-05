<?php

namespace App\Modules\Users;

use App\Http\Controllers\Controller;
use App\Modules\Users\Business\UserBusiness;
use App\Modules\Users\Dtos\ActualizarUsuarioDto;
use App\Modules\Users\Models\User;
use App\Modules\Users\Requests\ActualizarRolRequest;
use App\Modules\Users\Requests\ActualizarUsuarioActualRequest;
use App\Modules\Users\Requests\ActualizarPasswordRequest;
use App\Modules\Users\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function response;


class UsersController extends Controller
{
	private UserBusiness $userBusiness;

	public function __construct(UserBusiness $userBusiness) {
		$this->userBusiness = $userBusiness;
	}

	public function me() {
        $userId = $this->getUserId();
        $currentUser = UserBusiness::getById($userId);

		return new UserResource($currentUser);
	}
    
    public function actualizarPassword(ActualizarPasswordRequest $request) {
        
        $userId = $this->getUserid();
        
        UserBusiness::actualizarPassword(
            $userId,
            $request->get('password_actual'),
            $request->get('password_nueva' ),
        );
        
        return $this->json([]);
    }

    public function actualizarUsuarioActual(ActualizarUsuarioActualRequest $request) {
		$dto = ActualizarUsuarioDto::fromRequest($request);

        $dto->id = $this->getUserid();

		$updatedUser = $this->userBusiness->actualizarUsuario($dto);

		return new UserResource($updatedUser);
	}

	public function index(Request $request) {
		$collection = User::listar(
			$request->query('page' ,    0),
			$request->query('limit'  , 10),
			$request->query('filtros', []),
			$request->query('ordenes', []),
			[
				'with_relation' => [
					'rol',
					'onboardingUser.userPersonalData',
					'onboardingUser.business'
				]
			]
		);

		return UserResource::collection($collection);
	}
    
    public function show(int $id) {
        $this->validarAdministrador();
        return new UserResource(User::getById($id, [], [
            'with_relation' => [
                'rol',
                'onboardingUser.userPersonalData',
                'onboardingUser.business'
            ]
        ]));
}

	public function actualizarRol(int $userId, ActualizarRolRequest $request) {
		$this->validarAdministrador();

		$rolId = $request->rol_id;

		$user = $this->userBusiness->actualizarRol($userId, $rolId);

		return new UserResource($user);
	}

	public function habilitar(int $userId) {
		$this->validarAdministrador();

		$user = $this->userBusiness->habilitar($userId);

		return new UserResource($user);
	}

	public function deshabilitar(int $userId) {
		$this->validarAdministrador();

		$user = $this->userBusiness->deshabilitar($userId);

		return new UserResource($user);
	}

	public function solicitarBaja() {
		/** @var User $currentUser */
		$currentUser = Auth::user();

		UserBusiness::solicitarBaja($currentUser);

		return response()->json(['status' => 'Solicitud de baja enviada']);
	}
}