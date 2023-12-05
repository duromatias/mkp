<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Modules\Prendarios\Clients;
use App\Modules\Base\HttpLogger\HttpLoggerForGuzzle;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\HandlerStack;
use Psr\Http\Message\ResponseInterface;
use function config;
use function GuzzleHttp\json_decode;
use function storage_path;

/**
 * Description of PrendariosClient
 *
 * @author kodear
 */
class PrendariosClient {
    
    public  static string      $accessToken;
    private static ?HttpClient $httpClient = null;
    private string $token = '';

    /**
     * @deprecated
     */
    public function login(string $token) {
        // $this->get('/agencias/mis-unidades', [ 'access_token' => $token ]);
        
        static::getHttpClient()->get('/agencias/mis-unidades', [
            'query' => [ 'access_token' => $token ],
            'headers' => [
                'Accept'  => 'application/json',
            ]
        ]);
    }

    public function loginConApiKey(string $apiKey, string $email, int $agencyId) {
        $response = static::getHttpClient()->get('/auth/simulator', [
            'headers' => [
                'Accept'  => 'application/json',
                'x-api-key' => $apiKey,
            ],
            'query' => [
                'user' => $email,
                'agency' => $agencyId,
            ],
        ]);
        $data = $this->getParsedResponse($response);
        
        $this->token = $data['content'];
    }
    
    static public function getHttpClient(): HttpClient {
        if (!static::$httpClient) {
            $stack = HandlerStack::create();
            $logger = new HttpLoggerForGuzzle(config('prendarios.base_url'));
            $stack->push($logger->requestMiddleware());
            $stack->push($logger->responseMiddleWare());
            // my middleware

            static::$httpClient = new HttpClient([
                'base_uri' => config('prendarios.base_url'),
                'handler'  => $stack,
                'cookies'  => true,
                /*'stream'   => true,
                'read_timeout' => 180,
                'timeout'      => 180,*/
            ]);
        }
        
        return static::$httpClient;
    }
    
    static protected function logInfo($data) {
        file_put_contents(storage_path('logs/1.prendarios.log'), "{$data}\n", FILE_APPEND);        
    }
    
    private function getHeaders(array $merge = []): array {
        $headers = array_merge([
            'Accept' => 'application/json',
        ], $merge);
        if ($this->token) {
            $headers['X-Simulator-Auth'] = $this->token;
        }
        
        return $headers;
    }
    
    public function get(string $url, array $params = []) {
        $headers = $this->getHeaders();
        
        $response = static::getHttpClient()->get($url, [
            'query' => $params,
            'headers' => $headers
        ]);
        
        return $this->getParsedResponse($response);
    }
    
    public function post(string $url, array $data = []) {
        $headers = $this->getHeaders([
            'Content-Type' => 'application/json',
        ]);
        
        $response = static::getHttpClient()->post($url, [
            'headers' => $headers,
            'json'    => $data,
        ]);
        
		return $this->getParsedResponse($response);
    }
    
    public function getParsedResponse(ResponseInterface $response) {
        $body = $response->getBody()->getContents();
        
        $json = json_decode($body, true);
        if (json_last_error()) {
            throw new Exception('Formato de respuesta inesperada.');
        }
        
        if ($response->getStatusCode() < 200 || $response->getStatusCode() > 299) {
            
            $message = 'Error del servicio: ' . $body;

            throw new Exception($message, []);
        }

        return $json;
    }
    
    public function shell(string $method, string $uri, string $data = '', array $headers = []): array {
        $baseUrl  = config('prendarios.base_url');
        $url = $baseUrl . $uri;
        $command = implode(" ", [
            "curl -s '{$url}'",
            //"-X {$method}",
            "-H 'Accept: application/json, text/plain, */*'",
            "-H 'Content-Type: application/json;charset=UTF-8'",
            "-H 'X-Simulator-Auth: {$this->token}'",
            "--data-raw '{$data}'",
            "--compressed"
        ]);
        
        $ret = shell_exec($command);
        return json_decode($ret, true);
    }
}
