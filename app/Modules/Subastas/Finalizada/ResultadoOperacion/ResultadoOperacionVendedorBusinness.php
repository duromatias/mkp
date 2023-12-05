<?php

namespace App\Modules\Subastas\Finalizada\ResultadoOperacion;

use App\Modules\Publicaciones\Publicacion;

class ResultadoOperacionVendedorBusinness
{
    static public function actualizarVentaRealizada(int $publicacionId, bool $resultado)
    {
        $publicacion = Publicacion::getById($publicacionId,[],[
            'with_relation'=>['ultimaOferta'],
        ]);
        $oferta_ganadora_id = $publicacion->ultimaOferta ? $publicacion->ultimaOferta->id : null;
        return $publicacion->actualizarVentaRealizada($resultado, $oferta_ganadora_id);
    }
    
    static public function actualizarCalificacionComprador(int $publicacionId, string $calificacion)
    {
        $publicacion = Publicacion::getById($publicacionId);
        return $publicacion->actualizarCalificacionComprador($calificacion);
    }
    
    static public function actualizarObservacionesVendedor(int $publicacionId, string $observaciones)
    {
        $publicacion = Publicacion::getById($publicacionId);
        return $publicacion->actualizarObservacionesVendedor($observaciones);
    }

}