<?php

namespace App\Modules\Publicaciones\MisPublicaciones;

use App\Http\Controllers\Controller;
use App\Modules\Prendarios\Resources\ColorResource;
use App\Modules\Publicaciones\Publicacion;
use App\Modules\Publicaciones\PublicacionResource;
use App\Modules\Shared\Exceptions\BusinessException;
use App\Modules\Subastas\Resources\SubastaResource;
use App\Modules\Users\Models\User;
use App\Modules\Vehiculos\Resources\MarcaResource;
use App\Modules\Vehiculos\Resources\TipoCombustibleResource;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use function auth;

class MisPublicacionesController extends Controller
{
    public function index(Request $request) {
        $usuarioId = auth('api')->user() ? auth('api')->user()->id : null;
        $data = MisPublicacionesBusiness::listar(
            $usuarioId,
            $request->get('offset'  ,  0),
            $request->get('limit'   , 10),
            $request->get('filtros' , []),
            $request->get('ordenes' , []),
            $request->get('opciones', []),
        );
        
        $data['listado'] = PublicacionResource::collection($data['listado']);
        
        return $this->json($data);
    }
    
    public function obtenerMarcasDisponibles(Request $request) {
        return $this->json(Publicacion::obtenerMarcasDisponibles($request->get('filtros', [])));
    }
    
    public function obtenerModelosDisponibles(Request $request) {
        return $this->json(Publicacion::obtenerModelosDisponibles($request->get('filtros', [])));
    }
    
    
    public function show(Request $request, int $id) {
        $publicacion = MisPublicacionesBusiness::getById(
            $this->getUserid(), 
            $id
        );
        
        return new PublicacionResource($publicacion);
    }

	public function store(CrearPublicacionRequest $request) {
		$publicacionDto = CrearPublicacionDto::fromRequest($request);

		/** @var User $currentUser */
		$currentUser = auth('api')->user();

		if ($this->esParticular()) {
			$publicacion = MisPublicacionesBusiness::crearPorParticular($publicacionDto, $currentUser);
		}
		else if ($this->esAgencia()) {
			$publicacion = MisPublicacionesBusiness::crearPorAgencia($publicacionDto, $currentUser);
		}
		else {
			throw new AuthorizationException("Rol no autorizado");
		}

		return new PublicacionResource($publicacion);
	}
    
    public function actualizar(ActualizarRequest $request, $id) {
        $this->validarPuedeActualizar($id);
        
        $dto = CrearPublicacionDto::fromRequest($request);
        $publicacion = MisPublicacionesBusiness::actualizar($this->getUserId(), $id, $dto);
        
        return new PublicacionResource($publicacion);
    }
    
    public function eliminar(Request $request, $id) {
        $this->validarPuedeActualizar($id);
        MisPublicacionesBusiness::eliminar($this->getUserId(), $id, '');
    }
    
    public function validarPuedeActualizar($publicacionId) {
        /** @var User $user */
        $user = auth('api')->user();

        if (!$user) {
            throw new BusinessException('Debe ingresar a la plataforma');
        }
        
        MisPublicacionesBusiness::validarPuedeActualizar($user->id, $publicacionId);
    }
    
    public function obtenerPrecioMinimo() {
        return response()->json([
            'data' => [
                Publicacion::MONEDA_PESOS   => MisPublicacionesBusiness::obtenerPrecioMinimo(Publicacion::MONEDA_PESOS  ),
                Publicacion::MONEDA_DOLARES => MisPublicacionesBusiness::obtenerPrecioMinimo(Publicacion::MONEDA_DOLARES),
            ],
        ]);
    }

	public function opcionesFormulario() {
		$opcionesFormulario = MisPublicacionesBusiness::getOpcionesFormulario();

		return response()->json([
			'data' => [
				'precio_minimo' => $opcionesFormulario['precio_minimo'],
				'colores' => ColorResource::collection($opcionesFormulario['colores']),
				'tipos_combustible' => TipoCombustibleResource::collection($opcionesFormulario['tipos_combustible']),
				'marcas' => MarcaResource::collection($opcionesFormulario['marcas']),
				'subasta_disponible' => $opcionesFormulario['subasta_disponible'] ? new SubastaResource($opcionesFormulario['subasta_disponible']) : null
			]
		]);

	}
}