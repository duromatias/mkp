<?php

namespace App\Modules\Onboarding\Models;

use App\Modules\Onboarding\OnboardingRepository;


class Address extends OnboardingRepository
{
    protected $table = 'addresses';

	public function province() {
		return $this->belongsTo(Province::class, 'province_id');
	}
 
    public function obtenerDireccionCompleta(): string {
        $partes = array_filter([
            trim("{$this->street} {$this->number}"),
            $this->locality,
            $this->postal_code,
            $this->province->name,
        ], function($valor) {
            return "{$valor}" !== '';
        });
        
        return implode(', ', $partes);
    }
}
