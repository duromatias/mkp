<?php

namespace App\Modules\Google\Places;

use Illuminate\Support\Collection;
use SKAgarwal\GoogleApi\PlacesApi;

class PlacesService {
    
    private static PlacesApi $api;

    private static function getInstance(): PlacesApi {
        if (empty(static::$api)) {
            static::$api = new PlacesApi(config('google.places_api_key'), true);
        }

        return static::$api;
    }

    static public function buscar(string $text, ?string $sessionToken = null): Collection {
        return static::getInstance()->placeAutocomplete($text, [
            'location'     => '-34.438136,-65.036764',
            'radius'       => 1300000,
            'strictbounds' => 1,
            'types'        => ['establishment','address','geocode'],
            'sessiontoken' => $sessionToken,
			'language' => 'es'
        ])->get('predictions');
    }

    public static function obtenerDetalles(string $placeId, ?string $sessionToken = null): \stdClass {

        $response = static::getInstance()->placeDetails($placeId, [
            'sessiontoken' => $sessionToken,
        ])->get('result');

        $detalles = json_decode(json_encode($response));
        $latitud  = $detalles->geometry->location->lat;
        $longitud = $detalles->geometry->location->lng;
        
        $ubicacion = Ubicacion::parse($detalles->address_components);

        return (object) [
            'nombre'        => $detalles->name,
            'latitud'       => $latitud,
            'longitud'      => $longitud,
            'codigo_postal' => preg_replace('/([a-z]*)([0-9]*)/i','$2', $ubicacion->codigo_postal),
            'calle'         => $ubicacion->calle,
            'numero'        => $ubicacion->numero,
            'direccion'     => trim("{$ubicacion->calle} {$ubicacion->numero}"),
            'localidad'     => $ubicacion->localidad,
            'departamento'  => $ubicacion->departamento,
            'provincia'     => $ubicacion->provincia,
            'viewport'      => (object) [
                'ne_lat' => $detalles->geometry->viewport->northeast->lat,
                'ne_lng' => $detalles->geometry->viewport->northeast->lng,
                'sw_lat' => $detalles->geometry->viewport->southwest->lat,
                'sw_lng' => $detalles->geometry->viewport->southwest->lng,
            ],
            'original_data' => $detalles,
        ];
    }
}