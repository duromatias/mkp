<?php

namespace App\Modules\Subastas\Finalizada\ResultadoOperacion;

use App\Modules\Publicaciones\Publicacion;

class ResultadoOperacionCompradorBusinness
{     
    static public function actualizarCompraRealizada(int $publicacionId, bool $resultado)
    {
        $publicacion = Publicacion::getById($publicacionId);
        return $publicacion->actualizarCompraRealizada($resultado);
    }
    
    static public function actualizarCalificacionVendedor(int $publicacionId, string $calificacion)
    {
        $publicacion = Publicacion::getById($publicacionId);
        return $publicacion->actualizarCalificacionVendedor($calificacion);
    }
    
    static public function actualizarObservacionesComprador(int $publicacionId, string $observaciones)
    {
        $publicacion = Publicacion::getById($publicacionId);
        return $publicacion->actualizarObservacionesComprador($observaciones);
    }
}