<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Modules\Subastas\Finalizada\ResultadoOperacion;

use App\Modules\Publicaciones\Publicacion;
use App\Modules\Shared\Exceptions\BusinessException;
/**
 * Description of ResultadoOperacionBusiness
 *
 * @author manu
 */
class ResultadoOperacionBusiness {
    //put your code here
    
    static public function identificarOrigen(int $usuarioId, int $publicacionId){
        
        $publicacion = Publicacion::getById($publicacionId,[],[
            'with_relation'=>['ultimaOferta'],
        ]);
        
        if(is_null($publicacion->subasta)){      
            throw new BusinessException('La publicacion no pertenece a ninguna subasta');
        }
        

        if($usuarioId === $publicacion['usuario_id']){
            return 'vendedor';     
        }
        else{

            if(!$publicacion->ultimaOferta){
                throw new BusinessException('La publicacion no tuvo ninguna oferta');
            }
            
            if($publicacion->ultimaOferta->usuario_id === $usuarioId){
                return 'comprador';
            }
        }
        
        throw new BusinessException('El usuario no es ni comprador ni vendedor');
        
    }
    
    static public function actualizarOperacionRealizada(int $usuarioId ,int $publicacionId, bool $resultado) {
        
        $origen = self::identificarOrigen($usuarioId, $publicacionId);
        
        if($origen === 'vendedor'){
            return ResultadoOperacionVendedorBusinness::actualizarVentaRealizada($publicacionId, $resultado);
        }
        
        if($origen === 'comprador'){
            return ResultadoOperacionCompradorBusinness::actualizarCompraRealizada($publicacionId, $resultado);
        }              
    }
    
    static public function actualizarCalificacion(int $usuarioId ,int $publicacionId, string $calificaion) {
        
        $origen = self::identificarOrigen($usuarioId, $publicacionId);
        
        if($origen === 'vendedor'){
            return ResultadoOperacionVendedorBusinness::actualizarCalificacionComprador($publicacionId, $calificaion);
        }
        
        if($origen === 'comprador'){
            return ResultadoOperacionCompradorBusinness::actualizarCalificacionVendedor($publicacionId, $calificaion);
        }   
    }
    
    static public function actualizarObservaciones(int $usuarioId ,int $publicacionId, string $observaciones) {
        
        $origen = self::identificarOrigen($usuarioId, $publicacionId);
        
        if($origen === 'vendedor'){
            return ResultadoOperacionVendedorBusinness::actualizarObservacionesVendedor($publicacionId, $observaciones);
        }
        
        if($origen === 'comprador'){
            return ResultadoOperacionCompradorBusinness::actualizarObservacionesComprador($publicacionId, $observaciones);
        }   
    }
}
