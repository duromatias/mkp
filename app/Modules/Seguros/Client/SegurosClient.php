<?php

namespace App\Modules\Seguros\Client;

use App\Modules\Prendarios\Clients\BaseClient;
use function config;

class SegurosClient extends BaseClient {

    private string $accessToken = '';

    static protected function obtenerUrlBase(): string {
        return config('seguros.base_url');
    }

    protected function decodificarRespuesta($respuesta) {
        return $respuesta['response'];
    }

    public function loginConApiKey(string $apiKey) {
        $response = $this->post('/user/token', [], [
            'Accept'  => 'application/json',
            'x-api-key' => $apiKey,
        ]);

        $this->accessToken = $response;
    }

    public function listarModelosPorAño(int $año, ?string $busqueda = null): array {
        $respuesta = $this->get("/buscar/{$año}");

        if (trim($busqueda)) {
            $palabras = explode(' ',trim($busqueda));

            $respuesta = array_filter($respuesta, function($item) use($palabras) {
                $marca = $item['brand'];
                $label = $item['label'];
                $matchMarca = false;
                $coincidencias = 0;
                foreach($palabras as $palabra) {
                    if (stripos($label, $palabra) !== false) {
                        $coincidencias++;
                    }
                    if (stripos($marca, $palabra) !== false) {
                        $matchMarca = true;
                    }
                }
                return $matchMarca && $coincidencias === count($palabras);
            });
        }

        return $respuesta;
    }

    public function listarLocalidadesPorCodigoPostal(int $busqueda): array {
        $respuesta = $this->get("/combo/localidades/{$busqueda}");
        $lista = [];
        foreach($respuesta as $codigo_postal => $localidades) {
            foreach($localidades as $localidad) {
                $lista[] = [
                    'codigo_postal' => $codigo_postal,
                    'localidad'     => $localidad,
                ];
            }
        }
        return $lista;
    }

    public function listarSeguros($codia, $anio, $cp, $localidad, $provincia) {
        //@todo: terminar este método.

        return  $this->post('/adminse/cotizadormc', [
            'codigo'          => $codia,                 // Informado por el usuario
            'anio'            => $anio,                  // Informado por el usuario
            'codigoPostal'    => $cp,                    // Sale del usuario logugeado
            'localidad'       => $localidad,             // Sale del usuario logugeado
            'provincia'       => $provincia,             // Sale del usuario logugeado
            'token'           => $this->accessToken,
        ]);

    }
}
