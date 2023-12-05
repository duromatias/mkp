<?php

namespace App\Modules\Publicaciones\Ofertas\Businesses;

use App\Base\BusinessException;
use App\Modules\Parametros\Parametro;
use App\Modules\Publicaciones\Ofertas\Dtos\CreateOfertaDto;
use App\Modules\Publicaciones\Ofertas\Models\Oferta;
use App\Modules\Publicaciones\Publicacion;
use App\Modules\Subastas\Subasta;
use App\Modules\Users\Models\User;
use Illuminate\Support\Facades\DB;

class OfertaBusiness
{
	public static function listar(int $page = 1, int $limit = 0, array $filtros = [], array $ordenes = [], array $opciones = []) {
		return Oferta::listar($page, $limit, $filtros, $ordenes, $opciones);
	}

	public static function create(CreateOfertaDto $createOfertaDto): Oferta {
        
        return DB::transaction(function() use ($createOfertaDto) {
            
            $user = User::getById($createOfertaDto->usuario_id);
            $publicacion = Publicacion::getById($createOfertaDto->publicacion_id);

            if ($publicacion->usuario_id === $user->id) {
            	throw new BusinessException('No es posible ofertar una publicación propia');
			}

            if (!$publicacion->subasta_id) {
                throw new BusinessException('La publicación no pertenece a ninguna subasta');
            }

            if ($publicacion->subasta->estado === Subasta::ESTADO_CANCELADA) {
                throw new BusinessException('La subasta a la que pertenece la publicación se encuentra cancelada');
            }

            if (!$publicacion->subasta->abiertaRecepcionOfertas()) {
                throw new BusinessException('La subasta no se encuentra en el período de recepción de ofertas');
            }


            if (!$user->esAgenciaReady()) {
                throw new BusinessException('Para poder ofertar en una Subasta deberá ingresar al sistema de Onboarding y finalizar su registro');
            }

            return Oferta::create((array) $createOfertaDto);
        });
	}
    
    static public function obtenerValorIncrementoPorPublicacion(Publicacion $publicacion) {

		if ($publicacion->moneda === Publicacion::MONEDA_PESOS) {
			return (int) Parametro::getById(Parametro::ID_SUBASTAS_OFERTAS_INCREMENTO_ARS)->valor;
		}

		if ($publicacion->moneda === Publicacion::MONEDA_DOLARES) {
			return (int) Parametro::getById(Parametro::ID_SUBASTAS_OFERTAS_INCREMENTO_USD)->valor;
		}
    }
    
    static public function obtenerValorOfertaMinima(Publicacion $publicacion) {
        
        $incremento        = static::obtenerValorIncrementoPorPublicacion($publicacion);

        $montoUltimaOferta = $publicacion->ultimaOferta ? $publicacion->ultimaOferta->precio_ofertado : null;
        
        if ($montoUltimaOferta !== null) {
            return $montoUltimaOferta + $incremento;
        }
        
        return $publicacion->precio_base + $incremento;
    }
}