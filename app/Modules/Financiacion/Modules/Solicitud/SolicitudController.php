<?php

namespace App\Modules\Financiacion\Modules\Solicitud;

use App\Http\Controllers\Controller;
use App\Modules\Financiacion\Modules\Solicitud\Dtos\ActualizarDatosFinanciacionDto;
use App\Modules\Financiacion\Modules\Solicitud\Requests\ActualizarDatosFinanciacionRequest;
use App\Modules\Financiacion\Requests\GenerarSolicitudRequest;
use App\Modules\Prendarios\Resources\EstadoCivilResource;
use App\Modules\Prendarios\Resources\LocalidadResource;
use App\Modules\Publicaciones\Publicacion;
use App\Modules\Publicaciones\PublicacionResource;
use App\Modules\Users\Resources\UserResource;
use Exception;

class SolicitudController extends Controller
{

    private function obtenerBusiness(?Publicacion $publicacion = null): SolicitudBusiness {
        $user = null;
		try {
            $user = $this->getUser();
        } catch (Exception $e) {

        }

		return new SolicitudBusiness($user, $publicacion);
    }

	public function obtenerCuotas(Publicacion $publicacion) {
        $monto = $this->getRequest()->query('monto');
        $resultado = $this->obtenerBusiness($publicacion)->obtenerCuotas($monto);
		return $this->json(['data' => $resultado]);
	}

    public function obtenerCuotasYMontos(Publicacion $publicacion) {
        $business      = $this->obtenerBusiness($publicacion);
        $capital       = $this->input('capital');
        $cuotasYMontos = $business->obtenerCuotasYMontos($capital);

        return $this->json($cuotasYMontos);
    }

    /**
     * @deprected
     * @todo: BORRAR
     */
    public function obtenerCuotasPorUsuario(Publicacion $publicacion) {

        $this->getRequest()->validate(['capital' => 'required|int']);
		$capital = $this->getRequest()->input('capital');

        $resultado  = $this->obtenerBusiness($publicacion)->obtenerCuotasPorUsuario($capital);
        return $this->json($resultado);
    }

    public function obtenerPersonasPorDocumento(Publicacion $publicacion) {
        $this->getRequest()->validate(['documento' => 'required|string']);
		$documento = $this->input('documento');

        $rs = $this->obtenerBusiness($publicacion)->obtenerPersonasPorDocumento($documento);
        return $this->json($rs);
    }

	public function actualizarDatosFinanciacion(ActualizarDatosFinanciacionRequest $request) {

		$actualizarDatosFinanciacionDto = ActualizarDatosFinanciacionDto::fromArray($request->validated());

		$user = $this->obtenerBusiness()->actualizarDatosFinanciacion($actualizarDatosFinanciacionDto);

		return new UserResource($user);
	}

    public function puedeGenerar(Publicacion $publicacion) {
        $resultado = $this->obtenerBusiness($publicacion)->validarPuedeGenerar();
        return $this->json([ 'data' => $resultado ]);
    }

	public function generar(Publicacion $publicacion, GenerarSolicitudRequest $request) {

		$capital = $request->input('capital');
		$cantidadCuotas = $request->input('cantidad_cuotas');
        $cotizacionSeguroId = $request->input('cotizacionSeguroId');

		$resultado = $this->obtenerBusiness($publicacion)->generar($capital, $cantidadCuotas, $cotizacionSeguroId);

		return $this->json(['data' => $resultado]);
	}

    public function obtenerDatos(Publicacion $publicacion, int $operacionId) {
        $monto = $this->input('monto');
        $data = $this->obtenerBusiness($publicacion)->obtenerDatos($operacionId, $monto);
        $data['publicacion'] = new PublicacionResource($data['publicacion']);
        return $this->json($data);
    }

    public function listarLocalidades() {
        $localidades = SolicitudBusiness::listarLocalidades(
            $this->input('page' ,     0),
            $this->input('limit'  ,  10),
            $this->input('filtros',  []),
            $this->input('ordenes',  []),
            $this->input('opciones', [])
        );

        return LocalidadResource::collection($localidades);
    }

    public function obtenerDatosAuxiliares() {
        $usosVehiculo   = SolicitudBusiness::listarUsosVehiculo();
        $estadosCiviles = SolicitudBusiness::listarEstadosCiviles();
        $usuario        = $this->getUser();
        return $this->json([
            'usosVehiculo'   => $usosVehiculo,
            'estadosCiviles' => EstadoCivilResource::collection($estadosCiviles),
            'usuario'        => new UserResource($usuario),
        ]);
    }

    public function notificarErrorAlSolicitarCuotasPorUsuario(Publicacion $publicacion) {
        $business      = $this->obtenerBusiness($publicacion);
        $business->notificarErrorAlSolicitarCuotasPorUsuario();
        return $this->json([]);
    }

    public function notificarPoductoFaltante(Publicacion $publicacion) {
        $business = $this->obtenerBusiness($publicacion);
        $business->notificarPoductoFaltante();
        return $this->json([]);
    }
}
