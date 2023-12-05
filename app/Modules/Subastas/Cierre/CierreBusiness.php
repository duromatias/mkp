<?php

namespace App\Modules\Subastas\Cierre;

use App\Modules\Publicaciones\Ofertas\Models\Oferta;
use App\Modules\Publicaciones\Publicacion;
use App\Modules\Subastas\Cierre\Emails\PublicacionConOfertaGanadora;
use App\Modules\Subastas\Cierre\Emails\PublicacionSinOfertaGanadora;
use App\Modules\Subastas\Cierre\Emails\UsuarioOfertaGanadora;
use App\Modules\Subastas\Cierre\Emails\UsuarioOfertaNoGanadora;
use App\Modules\Subastas\Subasta;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

/**
 * Description of SubastaFinalizadaBusiness
 *
 * @author kodear
 */
class CierreBusiness {
    
    static public $debug = false;
    
    static public function notificarSubastaFinalizadaEnFecha(string $fecha) {
        $rs = Subasta::listar(1, 1, [
            'fecha_fin_ofertas' => $fecha
        ]);
        
        foreach($rs as $subasta) {
            static::notificarSubastaFinalizada($subasta);
        }
    }
    
    static public function notificarSubastaFinalizada(Subasta $subasta) {
        foreach($subasta->publicaciones as $publicacion) {
            static::verbose(" - Publicacion #{$publicacion->id} - {$publicacion->obtenerNombreVehiculo()}");
            $ofertaGanadora = static::obtenerOfertaGanadora($publicacion);
            if ($ofertaGanadora instanceof Oferta) {
                static::enviarEmail($publicacion->usuario->email, new PublicacionConOfertaGanadora($publicacion));
                static::enviarEmail($ofertaGanadora->usuario->email, new UsuarioOfertaGanadora($ofertaGanadora));
                
                $emails = static::obtenerEmailsOfertasNoGanadoras($publicacion);
                foreach($emails as $email) {
                    static::enviarEmail($email, new UsuarioOfertaNoGanadora($publicacion));
                }
            } else {
                static::enviarEmail($publicacion->usuario->email, new PublicacionSinOfertaGanadora($publicacion));
            }
        }
    }
    
    static public function enviarEmail(string $email, Mailable $mensaje) {
        if (static::$debug) {
            $mensaje->build();
            $className =  basename(str_replace('\\', '//', get_class($mensaje)));
            static::verbose("    Enviando a {$email}: {$mensaje->subject} ({$className})");
        } else {
            Mail::to($email)->send($mensaje);
        }
    }
    
    static private function verbose(string $texto) {
        if (static::$debug){
            echo "{$texto}\n";
        }
    }
    
    static public function obtenerOfertaGanadora(Publicacion $publicacion) {
        static $cache = [];
        
        if (isset($cache[$publicacion->id])) {
            return $cache[$publicacion->id];
        }
        
        $publicacion->load('ultimaOferta');
        $ofertaGanadora = $publicacion->ultimaOferta;
        if ($ofertaGanadora === null) {
            return null;
        }
        
        if ($ofertaGanadora->precio_ofertado > $publicacion->precio_esperado) {
            $cache[$publicacion->id] = $ofertaGanadora;
            return $ofertaGanadora;
        }
        return null;
    }
    
    static public function obtenerEmailsOfertasNoGanadoras(Publicacion $publicacion): array {
        $ofertaGanadora = static::obtenerOfertaGanadora($publicacion);
        $emails = [];
        
        foreach($publicacion->ofertas as $oferta) {            
            if ($oferta->usuario->email === $ofertaGanadora->usuario->email) {
                continue;
            }
            
            $emails[] = $oferta->usuario->email;
        }
        
        return array_unique($emails);
    }
}
