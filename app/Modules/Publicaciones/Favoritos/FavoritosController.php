<?php

namespace App\Modules\Publicaciones\Favoritos;


use App\Http\Controllers\Controller;
use App\Modules\Publicaciones\Favoritos\Businesses\FavoritosBussiness;
use App\Modules\Publicaciones\Favoritos\Resources\FavoritoResource;

class FavoritosController extends Controller
{
	public function create(int $publicacionId) {
		$this->validarAgencia();

		$userId = $this->getUserId();

		$favorito = FavoritosBussiness::create($publicacionId, $userId);

		return new FavoritoResource($favorito);
	}

	public function delete(int $publicacionId) {
		$this->validarAgencia();

		$userId = $this->getUserId();

		FavoritosBussiness::delete($publicacionId, $userId);

		return response()->json([], 204);
	}
}