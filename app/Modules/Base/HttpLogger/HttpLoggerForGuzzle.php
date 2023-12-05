<?php

namespace App\Modules\Base\HttpLogger;

use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HttpLoggerForGuzzle {
    
    private ?HttpLogger $logger = null;
    private ?LogHttp $log = null;
    private string $baseUrl = '';
    
    public function __construct($baseUrl) {
        $this->baseUrl = $baseUrl;
    }
    
    private function logger() {
        if ($this->logger === null) {
            $this->logger = new HttpLogger($this->baseUrl);
        }
        
        return $this->logger;
    }
    
    public function requestMiddleware() {
        return Middleware::mapRequest(function(RequestInterface $request) {
            $this->registrarPeticion($request);
            return $request;
        });
    }
    
    public function responseMiddleWare() {
        return Middleware::mapResponse(function(ResponseInterface $response) {
            $this->registrarRespuesta($response);
            return $response;
        });
    }
    
    private function registrarPeticion(RequestInterface $request) {
        $request->getBody()->rewind();
        $data      = $request->getBody()->getContents();
        $metodo    = $request->getMethod();
        $uri       = substr($request->getUri(), strlen($this->baseUrl));
        $headers   = $request->getHeaders();
        $this->log = $this->logger()->registrar($metodo, $uri, $data, $headers);
        $request->getBody()->rewind();
    }
    
    private function registrarRespuesta(ResponseInterface $response) {
        // La respuesta viene sin rebobinar. Tenemos que hacerlo para
        // poder obtener el contenido.
        $response->getBody()->rewind();
        $body = $response->getBody()->getContents();
        $statusCode = $response->getStatusCode();
        if ($this->log) {
            $this->log->actualizarRespuesta($response->getHeaders(), $statusCode, $body);
        }
        
        // No quitar. Rebobinamos la respuesta, porque el que la lea,
        // pueda leerla sin usar rewind. No necesariamente pasará de nuevo
        // por este método.
        $response->getBody()->rewind();
    }
}
