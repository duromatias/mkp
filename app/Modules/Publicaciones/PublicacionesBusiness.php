<?php


namespace App\Modules\Publicaciones;

use App\Modules\Users\Models\User;

class PublicacionesBusiness {
    
	static protected function getOpciones(?int $userId = null): array {
		$relations = [
			'usuario',
			'usuario.onboardingUser',
			'usuario.onboardingUser.userPersonalData',
			'usuario.onboardingUser.business',
			'usuario.onboardingUser.business.address',
			'usuario.onboardingUser.business.address.province',
			'tipoCombustible',
			'multimedia',
			'subasta',
			'favorito',
		];

		if ($userId && !User::getById($userId)->esParticular()) {
            $relations = array_merge($relations,
                [
                    'ultimaOferta.usuario.onboardingUser.userPersonalData',
                    'ultimaOfertaPropia'
                ]
            );
		}

		return [
			'with_relation' => $relations
		];
	}
    
    static public function obtenerUrlMarketplace(int $publicacionId): string {
        return config("app.spa_url") . '/publicaciones/' . $publicacionId;
    }
}