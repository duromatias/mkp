<?php

namespace App\Modules\Hubspot;

use App\Modules\Base\HttpLogger\HttpLoggerForGuzzle;
use App\Modules\Prendarios\Clients\BaseClient;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\HandlerStack;

class HubspotClient extends BaseClient
{
    static protected function obtenerUrlBase(): string {
        return config('app.hubspot.ip');
    }

    static protected function obtenerApiToken(): string {
        return config('app.hubspot.apitoken');
    }

    public function actualizarRedes(int $codigoAgencia, string $facebook, string $instagram){
        $respuesta = $this->post('/company/update',
            [
                'facebook' => $facebook,
                'instagram' => $instagram,
                'codigoAgencia' => $codigoAgencia,
            ],
            [
                'Accept'  => 'application/json',
                'Content-Type' => 'application/json',
                'apitoken' => static::obtenerApiToken(),
            ],
        );

        return $respuesta;
    }

    public function obtenerRedes(int $codigoAgencia){
        $respuesta = $this->get("/company/redes/{$codigoAgencia}",
            [],
            [
                'apitoken' => static::obtenerApiToken(),
            ],
        );

        return $respuesta;
    }
}
