<?php

namespace App\Modules\Vehiculos\Business;

use App\Base\Repository\RepositoryException;
use App\Modules\Onboarding\Auth\AuthBusiness;
use App\Modules\Prendarios\Clients\PrendariosClient;
use App\Modules\Prendarios\Models\Color;
use App\Modules\Vehiculos\InfoAutoCarClient;
use App\Modules\Vehiculos\PrecioSugeridoNoDisponibleException;
use App\Modules\Vehiculos\TipoCombustible;

class VehiculosBusiness    
{
	static public function listarMarcas(array $filtros) {
        
        return static::aplicarQueryString($filtros, function($filtros) {
            $rs = InfoAutoCarClient::make()->getBrands(1, 100, $filtros);
            
            // Para infoauto list_price significa precios de CERO KILOMETRO,
            // mientras que prices_from y prices_to hacen referencia a precios
            // de usados.
            // Para no modificar el front, se considera que si viene list_price=true
            // se debe modificar el prices_to por el año actual.
            return array_filter($rs, function($marca) {
                if ($marca->list_price === true) {
                    $marca->prices_to = (int) date('Y');
                }
                return $marca;
            });
        });
	}

	static public function listarModelosPorMarca(int $brandId, array $filtros) {
        
        // Para infoauto list_price significa CERO KILOMETRO
        // Se consideran CERO KILOMETRO aquellos que se pidan por el año actual
        // Por tanto, cuando se pide por un modelo de año acutal, hay que cambiar
        // el filtro de price_at=YYYY por list_price=true, caso contraro no se
        // obtienen los modelos del año actual, dado que price_at se refiere
        // a usados.
        if (!empty($filtros['price_at']) && ((int)$filtros['price_at']) === ((int)date('Y'))) {
            unset($filtros['price_at']);
            $filtros['list_price'] = true;
        }
        
        return static::aplicarQueryString($filtros, function($filtros) use($brandId) {
            return InfoAutoCarClient::make()->getModelsByBrand($brandId, 1 ,100 , $filtros);
        }, 'description');
	}
    
    static private function aplicarQueryString(array $filtros, callable $fn, string $atributo = 'name') {
        $query_string = $filtros['query_string'] ?? null;
        
        // No mandamos la query al servidor,
        // El filtro se hace localmente.
        unset($filtros['query_string']);
        
        $rs = $fn($filtros);
        //print_r($rs);
        if ($query_string) {
            $rs = array_filter($rs, function($row) use($query_string, $atributo) {
                return strpos(strtolower($row->$atributo), strtolower($query_string)) !== false;
            });
        }

		return $rs;
    }

	static public function getOneModelo(int $codia, bool $usarCache = true) {
        static $cache = [];
        
        if (isset($cache[$codia]) && $usarCache) {
            return $cache[$codia];
        }
        
		$modelo = InfoAutoCarClient::make()->getModelByCodia($codia);

        $cache[$codia] = $modelo;
		return $modelo;
	}

	static public function getPrecioModelo(int $codia, int $year) {
		$url = '/agencias/lite/simulador/getModelPrice';

        
        try {
            $response = (new PrendariosClient)->get($url, [
                'codia' => $codia,
                'year'  => $year,
                'access_token' => AuthBusiness::obtenerAccessTokenUsuarioConfigurado(),
            ]);
        } catch (\Exception $e) {
             throw new PrecioSugeridoNoDisponibleException('Ocurrió un error al consultar el precio.');
        }
        
		return $response['price'];
	}

	static public function listarColores() {
		return Color::listar(1, 0, [], ['Order' => 'asc']);
	}
    
    static public function listarTiposCombustible() {
        return TipoCombustible::listar();
    }
    
    static public function validarAño($codia, $año): bool {
        $modeloInfo = static::getOneModelo($codia);        
        $minAño     = $modeloInfo->prices_from;
        $maxAño     = $modeloInfo->prices_to;
        
		if ($año < $minAño || $año > $maxAño) {
			return false;
		}
        
        return true;
    }

	static public function validarColor(string $color): bool {
		try {
			Color::getOne(['Nombre' => $color]);
		} catch (RepositoryException $repositoryException) {
			return false;
		}
        
        return true;
	}
}