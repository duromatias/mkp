<?php

namespace App\Modules\Seguros\Cotizaciones;

use App\Http\Controllers\Controller;
use App\Modules\Prendarios\Resources\LocalidadResource;use Illuminate\Http\Request;

class CotizacionesController extends Controller {

    public function listar(Request $request) {
        $rs = CotizacionesBusiness::listar(
            $request->query("codia"),
            $request->query("anio"),
            $request->query("cp"),
            $request->query("localidad"),
            $request->query("provincia")
        );
        return $this->json($rs);
    }
    
    public function listarAños() {
        $rs = CotizacionesBusiness::listarAños();
        return $this->json($rs);
    }
    
	public function listarModelosPorAño(int $año) {
        $filtros  = $this->input('filtros');
        $busqueda = $filtros['busqueda'] ?? null;
        $rs       = CotizacionesBusiness::listarModelosPorAño($año, $busqueda);
        return $this->json($rs);
	}
    
    public function listarLocalidades() {
        $rs = CotizacionesBusiness::listarLocalidades(
            $this->input('page' ,     0),
            $this->input('limit'  ,  10),
            $this->input('filtros',  []),
            $this->input('ordenes',  []),
            $this->input('opciones', [])
        );
        return LocalidadResource::collection($rs);
    }
}
