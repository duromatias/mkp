<?php

namespace App\Modules\Publicaciones\Subastas;

use App\Http\Controllers\Controller;
use App\Modules\Publicaciones\PublicacionResource;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;


class SubastasController extends Controller
{
	public function listarHome(Request $request) {
		if (!$this->esAgencia() && !$this->esAdministrador()) {
			throw new AccessDeniedHttpException('No presenta el rol de administrador o agencia');
		}

		$data = HomeBusiness::listar(
            $this->getUserIdNull(),
			$request->get('offset'  ,  0),
			$request->get('limit'   , 10),
			$request->get('filtros' , []),
			$request->get('ordenes' , []),
			$request->get('opciones', []),
		);

		return $this->listar($data);
	}
    
	public function listarMisPublicaciones(Request $request) {
		if (!$this->esAgencia() && !$this->esAdministrador()) {
			throw new AccessDeniedHttpException('No presenta el rol de administrador o agencia');
		}

		$data = MisPublicacionesBusiness::listar(
            $this->getUserIdNull(),
			$request->get('offset'  ,  0),
			$request->get('limit'   , 10),
			$request->get('filtros' , []),
			$request->get('ordenes' , []),
			$request->get('opciones', []),
		);

		return $this->listar($data);
	}
    
    public function listarMisPujas(Request $request) {
		if (!$this->esAgencia() && !$this->esAdministrador()) {
			throw new AccessDeniedHttpException('No presenta el rol de administrador o agencia');
		}
        
		$data = MisPujasBusiness::listar(
            $this->getUserId(),
			$request->get('offset'  ,  0),
			$request->get('limit'   , 10),
			$request->get('filtros' , []),
			$request->get('ordenes' , []),
			$request->get('opciones', []),
		);

		return $this->listar($data);        
    }
    
    public function listarMisFavoritos(Request $request) {
		if (!$this->esAgencia() && !$this->esAdministrador()) {
			throw new AccessDeniedHttpException('No presenta el rol de administrador o agencia');
		}
        
		$data = MisFavoritosBusiness::listar(
            $this->getUserId(),
			$request->get('offset'  ,  0),
			$request->get('limit'   , 10),
			$request->get('filtros' , []),
			$request->get('ordenes' , []),
			$request->get('opciones', []),
		);

		return $this->listar($data);        
    }
    
    public function listar($data ) {

		$data['listado'] = PublicacionResource::collection($data['listado']);

		return $this->json($data);
        
    }
}