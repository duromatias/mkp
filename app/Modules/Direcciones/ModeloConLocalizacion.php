<?php

namespace App\Modules\Direcciones;

trait ModeloConLocalizacion {
 
    public function obtenerDireccionCompleta(): string {
        $partes = array_filter([
            trim("{$this->calle} {$this->numero}"),
            $this->localidad,
            $this->codigo_postal,
            $this->provincia
        ], function($valor) {
            return "{$valor}" !== '';
        });
        
        return implode(', ', $partes);
    }
 
    public function obtenerDireccionParcial(): string {
        $partes = array_filter([
            $this->localidad,
            $this->codigo_postal,
            $this->provincia
        ], function($valor) {
            return "{$valor}" !== '';
        });
        
        return implode(', ', $partes);
    }
}