<?php

namespace App\Modules\Financiacion;

use App\Http\Controllers\Controller;
use App\Modules\Financiacion\Businesses\FinanciacionBusiness;
use App\Modules\Financiacion\Requests\VerificarFinanciacionRequest;
use App\Modules\Users\Models\User;
use Illuminate\Support\Facades\Auth;

class FinanciacionController extends Controller
{
	public function verificar(VerificarFinanciacionRequest $request) {
		/** @var User $user */
		$user = Auth::user();

		$brandName = $request->input('brand_name');
		$modelName = $request->input('model_name');
		$year = $request->input('year');

		$fullName = "{$brandName} {$modelName}";

		return response()->json([
			'financiable' => FinanciacionBusiness::verificar($fullName, $year)
		]);
	}
}