<?php

namespace App\Modules\Google\Places;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlacesController extends Controller
{

	public function buscar(Request $request) {
		$text = $request->query('text');
		$sessionToken =  $request->query('sessionToken');

		$result = PlacesService::buscar($text, $sessionToken);

		return JsonResource::collection($result);
	}

	public function obtenerDetalles(string $place_id, Request $request) {
		$sessionToken = $request->query('sessionToken');

		$result = (array) PlacesService::obtenerDetalles($place_id, $sessionToken);

		return $this->json($result);
	}
}
