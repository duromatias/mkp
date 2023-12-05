<?php

namespace App\Modules\Publicaciones\MisPublicaciones;

use App\Modules\Publicaciones\Multimedia\MultimediaBusiness;
use App\Modules\Publicaciones\Publicacion;

class ActualizarRequest extends CrearPublicacionRequest {
    
    public function validarAnio(){
        return;
    }
    
    protected function cantidadFotosExistentes(): int {
        $id = $this->route('id');
        return MultimediaBusiness::obtenerCantidadFotos($id);
    }
    
    protected function cantidadVideosExistentes(): int {
        $id = $this->route('id');
        return MultimediaBusiness::obtenerCantidadVideos($id);
    }
    
    protected function validarDatosSubasta(int $subastaId) {
        $publicacion = Publicacion::getById($this->route('id'));
        
        // Si se está mandando un id de subasta igual al que ya tiene,
        // no hay validación que hacer.
        if ($publicacion->subasta_id === $subastaId) {
            return;
        }
        
        parent::validarDatosSubasta($subastaId);
    }
}
