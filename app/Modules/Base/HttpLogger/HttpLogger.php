<?php

namespace App\Modules\Base\HttpLogger;

use Illuminate\Support\Facades\DB;

class HttpLogger {
    
    static private $procesoId = null;
    private $rutaBase;
    
    public function __construct(string $rutaBase) {
        $this->rutaBase = $rutaBase;
    }
    
    public function registrar(string $metodo, string $uri, $data = null, array $headers = []) {
        
        try {
            $peticionData = static::codificarDatos($data);
            
            $row = new LogHttp();
            $row->procesoId       = $this->obtenerProcesoId();
            $row->usuarioId       = $this->obtenerUsuarioId();
            $row->email           = $this->obtenerUsuarioEmail();
            $row->rutaBase        = $this->rutaBase;
            $row->metodo          = $metodo;
            $row->uri             = $uri;
            $row->peticionHeaders = json_encode($headers);
            $row->peticionData    = $peticionData;
            $row->tsInicio        = microtime(true);
            $row->guardar();
        } catch (\Throwable $e) {
            // No hacer nada...
        }
        
        return $row;
    }
    
    private static function codificarDatos(?string $data) {
        $array = json_decode($data, true);
        if (json_last_error()) {
            return $data; 
        }
        
        $codificado = $data;
        if (is_array($array)) {
            //$toBeStored = $data;
            array_walk_recursive($array, function(&$valor) {
                if (is_string($valor) && strlen($valor) > 1024) {
                    $valor = '**TRUNCATED**';
                }
            });
            $codificado = json_encode($array);
        }
        
        return $codificado;
    }
    
    static public function obtenerProcesoId() {
        if (static::$procesoId === null) {
            static::$procesoId = number_format(microtime(true), 4, '', '');
        }
        
        return static::$procesoId;
    }
    
    private function obtenerUsuario() {
        return auth('api')->user();
    }
    
    private function obtenerUsuarioId() {
        $user = $this->obtenerUsuario();
        if (!$user) {
            return;
        }
        
        return $user->id;
    }
    
    private function obtenerUsuarioEmail() {
        $user = $this->obtenerUsuario();
        if (!$user) {
            return;
        }
        
        return $user->email;
    }
    
    static public function borrarRegistrosViejos() {
        
        DB::query("SELECT * FROM log_http WHERE created_at < DATE_ADD(NOW(), INTERVAL -30 DAY)");
    }
}