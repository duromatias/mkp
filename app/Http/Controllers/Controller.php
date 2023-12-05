<?php

namespace App\Http\Controllers;

use App\Modules\Users\Business\UserBusiness;
use App\Modules\Users\Models\Rol;
use App\Modules\Users\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public function validatedInput(string $name, $validations = null) {
        $request     = $this->getRequest();
        $value       = $request->input($name);
        $data        = $request->all();
        $validations = Validator::make($data, [$name => $validations]);
        $validations->validate();
        return $value;
    }
    
    public function input($key = null, $default = null) {
        return $this->getRequest()->input($key, $default);
    }
    
    public function json($resource): JsonResource {
        return new JsonResource($resource);
    }
    
    public function jsonCollection($collection): AnonymousResourceCollection {
        return JsonResource::collection($collection);
    }
    
    protected function getRequest(): Request {
        return App::make(Request::class);
    }
    
	protected function esAgencia() : bool {
		/** @var User $currentUser */
		$currentUser = auth('api')->user();

		return $currentUser && $currentUser->esAgencia();
	}

	protected function esParticular() : bool {
		/** @var User $currentUser */
		$currentUser = auth('api')->user();

		return $currentUser && $currentUser->esParticular();
	}

	protected function esAdministrador() : bool {
		/** @var User $currentUser */
		$currentUser = auth('api')->user();

		return $currentUser && $currentUser->esAdministrador();
	}

	public function validarAdministrador() {
		if(!$this->esAdministrador()) {
			throw new AccessDeniedHttpException('Su usuario no presenta el rol de administrador');
		}
	}

	public function validarParticular() {
		if(!$this->esParticular()) {
			throw new AccessDeniedHttpException('Su usuario no presenta el rol de particular');
		}
	}

	public function validarAgencia() {
		if(!$this->esAgencia()) {
			throw new AccessDeniedHttpException('Su usuario no presenta el rol de agencia');
		}
	}

    protected function getUserId() {
        return auth('api')->user()->id;
    }

    protected function getUserIdNull(): ?int {
        return auth('api')->user() ? auth('api')->user()->id : null;
    }

    protected function getUser(): User {
        $id = $this->getUserid();
        return UserBusiness::getById($id);
    }
}
