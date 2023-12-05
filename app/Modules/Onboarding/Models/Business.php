<?php

namespace App\Modules\Onboarding\Models;

use App\Modules\Onboarding\OnboardingRepository;
use Illuminate\Database\Eloquent\Builder;

class Business extends OnboardingRepository
{
    protected $table = 'businesses';

    public function address() {
    	return $this->hasOne(Address::class, 'addressable_id', 'id');
	}
    
    public function getFormattedPhoneAttribute(): string {
    	if (!$this->marketplace_phone) {
			return '';
		}

		return $this->formatearTelefono($this->marketplace_phone);
	}


    public function formatearTelefono(string $original): string {
        list($caracteristica, $telefono) = explode(' ', $original);

        if (substr($telefono, 0 , 2) === "15") {
            $telefono = substr($telefono, 2);
        }
        
        if ($caracteristica[0]==="0") {
            $caracteristica = substr($caracteristica, 1);
        }
        
        return "549{$caracteristica}{$telefono}";
    }
    
    static public function aplicarFiltros(Builder $query, array $filtros) {
        foreach($filtros as $nombre => $valor) {
            $query->where('name', $valor);
        }
    }
    
    static public function getByName(string $name): self {
        return static::getOne(['name' => $name]);
    }
}
