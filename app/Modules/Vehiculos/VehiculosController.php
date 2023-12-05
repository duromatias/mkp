<?php

namespace App\Modules\Vehiculos;

use App\Http\Controllers\Controller;
use App\Modules\Prendarios\Resources\ColorResource;
use App\Modules\Vehiculos\Business\VehiculosBusiness;
use App\Modules\Vehiculos\Resources\MarcaResource;
use App\Modules\Vehiculos\Resources\ModeloResource;
use App\Modules\Vehiculos\Resources\TipoCombustibleResource;
use Illuminate\Http\Request;

class VehiculosController extends Controller
{
    
	public function listarMarcas(Request $request) {
		$marcas = VehiculosBusiness::listarMarcas($request->get('filtros', []));

		return MarcaResource::collection($marcas);
	}

	public function listarModelos(int $marcaId, Request $request) {
		$modelos = VehiculosBusiness::listarModelosPorMarca($marcaId, $request->get('filtros', []));

		return ModeloResource::collection($modelos);
	}
    
	public function obtenerModelo(int $codia, int $year) {        
		$modelo = VehiculosBusiness::getOneModelo($codia);

		return new ModeloResource($modelo);
	}

	public function obtenerPrecioSugerido(Request $request) {
        
        $this->validarAgencia();
        
		$codia = $request->query('codia');
		$anio  = $request->query('anio');
        
		return $this->json([
            'data' => VehiculosBusiness::getPrecioModelo($codia, $anio),
        ]);
	}


	public function listarColores() {
		$colores = VehiculosBusiness::listarColores();

		return ColorResource::collection($colores);
	}
    
    public function listarTiposCombustible() {
        $rs = VehiculosBusiness::listarTiposCombustible();
        return TipoCombustibleResource::collection($rs);
    }
}