<?php

namespace App\Modules\Publicaciones\Home;

use App\Http\Controllers\Controller;
use App\Modules\Publicaciones\Publicacion;
use App\Modules\Publicaciones\PublicacionResource;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request) {
        $data = HomeBusiness::listar(
            $this->getUserIdNull(),
            $request->get('offset'  ,  0),
            $request->get('limit'   , 10),
            $request->get('filtros' , []),
            $request->get('ordenes' , []),
            $request->get('opciones', []),
        );
        
        $data['listado'] = PublicacionResource::collection($data['listado']);
        
        return $this->json($data);
    }
    
    public function show(Request $request, int $id) {
        $publicacion = HomeBusiness::obtenerUna(
            $this->getUserIdNull(), 
            $id
        );
        
        return new PublicacionResource($publicacion);
    }

    public function contarClick(int $id) {
    	$publicacion = Publicacion::getById($id);

    	$userId = $this->getUserIdNull();

    	if ($this->esAdministrador() || ($userId && $publicacion->usuario_id === $userId)) {
    		abort(403);
		}

    	HomeBusiness::contarClick($id);

    	return response()->json([]);
	}
    
    public function obtenerMarcasDisponibles(Request $request) {
        return $this->json(Publicacion::obtenerMarcasDisponibles($request->get('filtros', [])));
    }
    
    public function obtenerModelosDisponibles(Request $request) {
        return $this->json(Publicacion::obtenerModelosDisponibles($request->get('filtros', [])));
    }
    
}