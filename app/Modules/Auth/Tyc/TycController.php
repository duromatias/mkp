<?php

namespace App\Modules\Auth\Tyc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class TycController extends Controller
{

	public function get()
	{
        //@todo: usar return $this->json(['tyc' => ... ]) en su lugar
		return new JsonResource(['tyc' => TycBusiness::get()]);
	}

	public function update(TycRequest $request)
	{
		$terminosCondiciones = $request->get('tyc');

		TycBusiness::update($terminosCondiciones);

        //@todo: usar return $this->json([]) en su lugar
		return response()->json([], 204);
	}
}