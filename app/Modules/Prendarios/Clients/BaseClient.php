<?php

namespace App\Modules\Prendarios\Clients;

use App\Modules\Base\HttpLogger\HttpLoggerForGuzzle;
use Exception;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\HandlerStack;
use Psr\Http\Message\ResponseInterface;
use function GuzzleHttp\json_decode;

class BaseClient {
    
    private static ?HttpClient $httpClient = null;
    
    protected static function obtenerUrlBase(): string {
        return '';
    }
    
    static public function getHttpClient(): HttpClient {
        if (!static::$httpClient) {
            $stack = HandlerStack::create();
            $logger = new HttpLoggerForGuzzle(static::obtenerUrlBase());
            $stack->push($logger->requestMiddleware());
            $stack->push($logger->responseMiddleWare());

            static::$httpClient = new HttpClient([
                'base_uri' => static::obtenerUrlBase(),
                'handler'  => $stack,
                'cookies'  => true,
            ]);
        }
        
        return static::$httpClient;
    }
    
    private function getHeaders(array $merge = []): array {
        $headers = array_merge([
            'Accept' => 'application/json',
        ], $merge);
        
        return $headers;
    }
    
    public function get(string $url, array $params = [], array $headers = []) {
        $headers = array_merge($this->getHeaders(), $headers);
        
        $response = static::getHttpClient()->get($url, [
            'query' => $params,
            'headers' => $headers
        ]);
        
        return $this->getParsedResponse($response);
    }
    
    public function post(string $url, array $data = [], array $headers = []) {
        $headers = array_merge($this->getHeaders([
            'Content-Type' => 'application/json',
        ]), $headers);
        
        $response = static::getHttpClient()->post($url, [
            'headers' => $headers,
            'json'    => $data,
        ]);
        
		return $this->getParsedResponse($response);
    }
    
    public function getParsedResponse(ResponseInterface $response) {
        $body = $response->getBody()->getContents();
        
        $array = json_decode($body, true);
        if (json_last_error()) {
            throw new Exception('Formato de respuesta inesperada.');
        }
        
        if ($response->getStatusCode() < 200 || $response->getStatusCode() > 299) {
            
            $message = 'Error del servicio: ' . $body;

            throw new Exception($message, []);
        }

        return $this->decodificarRespuesta($array);
    }
    
    protected function decodificarRespuesta($respuesta) {
        return $respuesta;
    }
}
