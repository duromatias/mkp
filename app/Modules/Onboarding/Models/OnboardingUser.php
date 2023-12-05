<?php

namespace App\Modules\Onboarding\Models;

use App\Modules\Onboarding\OnboardingRepository;
use Illuminate\Database\Eloquent\Builder;

class OnboardingUser extends OnboardingRepository
{
    protected $table = 'users';

    public function userPersonalData() {
    	return $this->hasOne(UserPersonalData::class, 'user_id');
	}

	public function business() {
    	return $this->belongsTo(Business::class);
	}

	public function address() {
    	return $this->hasOne(Address::class, 'addressable_id');
	}

	static public function aplicarFiltros(Builder $query, array $filtros) {
		parent::aplicarFiltros($query, $filtros);

		foreach($filtros as $nombre => $valor) {
			/* Where filters */
			if (in_array($nombre, ['email'])) {
				$query->where($nombre, $valor);
			}
		}
	}
}
