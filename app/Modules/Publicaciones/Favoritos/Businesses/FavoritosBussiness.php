<?php

namespace App\Modules\Publicaciones\Favoritos\Businesses;

use App\Base\BusinessException;
use App\Base\Repository\RepositoryException;
use App\Modules\Publicaciones\Favoritos\Models\Favorito;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FavoritosBussiness
{
	public static function create(int $publicacionId, int $userId): Favorito {
		try {
			return Favorito::crear(['publicacion_id' => $publicacionId, 'usuario_id' => $userId]);
		} catch (RepositoryException $exception) {
			throw new BusinessException('La publicación ya se encuentra agregada a favoritos');
		}
	}

	public static function delete(int $publicacionId, int $userId): int {
		try {
			$favorito = Favorito::getOne(['publicacion_id' => $publicacionId, 'usuario_id' => $userId]);
		} catch (RepositoryException $exception) {
			throw new NotFoundHttpException('No se encontró el registro');
		}

		return $favorito->delete();
	}
}