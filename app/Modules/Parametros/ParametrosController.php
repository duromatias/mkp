<?php

namespace App\Modules\Parametros;

use App\Http\Controllers\Controller;
use App\Modules\Parametros\Requests\ParametroMassUpdateRequest;


class ParametrosController extends Controller
{
	public function index() {
		$parametros = Parametro::listar();

		return ParametroResource::collection($parametros);
	}

	public function massUpdate(ParametroMassUpdateRequest $request) {
		/** @var Parametro[] $parametros */
		$data = $request->input('parametros');

		$parametros = Parametro::massUpdate($data);

		return $parametros;
	}
}