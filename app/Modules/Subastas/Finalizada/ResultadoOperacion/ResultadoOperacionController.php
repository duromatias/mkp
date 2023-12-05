<?php

namespace App\Modules\Subastas\Finalizada\ResultadoOperacion;

use App\Http\Controllers\Controller;
use function auth;
use App\Modules\Subastas\Finalizada\ResultadoOperacion\Requests\ActualizarCalifiacionRequest;
use App\Modules\Subastas\Finalizada\ResultadoOperacion\Requests\ActualizarOperacionRequest;
use App\Modules\Subastas\Finalizada\ResultadoOperacion\Requests\ActualizarObservacionesRequest;


class ResultadoOperacionController extends Controller
{
    
    public function actualizarOperacionRealizada(ActualizarOperacionRequest $request) {
        $publicacionId = $request->get('publicacion_id');
        $resultado     = $request->get('resultado');
        $usuarioId     = auth('api')->id();
        return $this->json(['operacion_realizada' => ResultadoOperacionBusiness::actualizarOperacionRealizada($usuarioId ,$publicacionId, $resultado)]);
    }
    
    public function actualizarCalificacion(ActualizarCalifiacionRequest $request){
        $publicacionId = $request->get('publicacion_id');
        $calificacion  = $request->get('calificacion');
        $usuarioId     = auth('api')->id();
        return $this->json(['calificacion' => ResultadoOperacionBusiness::actualizarCalificacion($usuarioId ,$publicacionId, $calificacion)]);
    }
    
    public function actualizarObservaciones(ActualizarObservacionesRequest $request){
        $publicacionId = $request->get('publicacion_id');
        $observaciones = $request->get('observaciones');
        if($observaciones === null){
            $observaciones = '';
        }
        $usuarioId     = auth('api')->id();
        return $this->json(['calificacion' => ResultadoOperacionBusiness::actualizarObservaciones($usuarioId ,$publicacionId, $observaciones)]);
    }

}