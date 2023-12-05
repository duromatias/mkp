<?php

namespace App\Base\Debug;

class MedicionDeTiempos {
    
    private $mtInicio = 0;
    private $mtFin    = 0;
    private $nombre   = '';
    
    protected static $mediciones = [];
    
    static public function comenzar(string $nombre): self {
        $medicion = new self($nombre);
        
        if (empty(static::$mediciones)) {
            register_shutdown_function(function() {
                static::log('-----------------------------------');
                foreach(static::$mediciones as $medicion) {
                    $medicion->registrar();
                }
            });
        }
        
        static::$mediciones[] = $medicion;
        
        return $medicion;
    }
    
    public function __construct(string $nombre) {
        $this->nombre   = $nombre;
        $this->mtInicio = microtime(true);
    }
    
    public function terminar() {
        $this->mtFin = microtime(true);
    }
    
    public function obtenerTiempo() {
        return $this->mtFin - $this->mtInicio;
    }
    
    public function registrar() {
        $tiempo = $this->obtenerTiempo();
        static::log("Medicion: {$this->nombre}: {$tiempo}");
    }
    
    static public function log(string $string) {
        $fechaHora = date('Y-m-d H:i:s');
        file_put_contents(storage_path('logs/1.mediciones.log'), "{$fechaHora} {$string}\n", FILE_APPEND);
    }
}