<?php

namespace App\Modules\Base\HttpLogger;

use App\Base\Repository\ModelRepository;

class LogHttp extends ModelRepository {
    
    protected $table = 'log_http';

    public function actualizarRespuesta($headers, $statusCode, $data): void {
        try {
            $this->respuestaHeaders = $headers;
            $this->respuestaData    = $data;
            $this->statusCode       = $statusCode;
            if ($statusCode< 200 || $statusCode > 299) {
                $this->error = 1;
            }
            $this->tsTotal          = microtime(true) - $this->tsInicio;

            $this->guardar();
        } catch(\Throwable $e) {
            // No hacer nada
        }
    }
}