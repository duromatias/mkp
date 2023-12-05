<?php

namespace App\Modules\Parametros;

class ParametrosApp {
    
    static private $registradores = [];
    
    static public function registrar(callable $fn): void {
        static::$registradores[] = $fn;
    }
    
    static public function obtenerEnFormatoAsociativo(): array {
        $data = [];
        foreach(static::obtenerParametrosRegistrados() as $parametro) {
            $data[$parametro->obtenerNombreParaExposicion()] = $parametro->valor;
        }
        
        return $data;
    }
    
    static public function obtenerParametrosRegistrados(): array {
        $data = [];
        foreach(static::$registradores as $registrador) {
            $tmp = $registrador();
            $parametros = is_array($tmp) ? $tmp : [$tmp];
            foreach($parametros as $parametro) {
                if (!($parametro instanceof Parametro)) {
                    throw new \Exception('Se esperaba una instancia de parámetro');
                }
                $data[] = $parametro;
            }
        }
        
        return $data;
    }
}
