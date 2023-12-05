<?php

namespace App\Modules\Prendarios\Clients;

use DateTime;

class InformacionCrediticia {
    
    public $data;
    
    public function __construct(array $data) {
        $this->data = $data;
    }
    
    public function obtenerFechaNacimiento(): string {
        return DateTime::createFromFormat('Ymd', $this->data['cliente']['veraz']['fecha_nacimiento'])->format('Y-m-d');
    }

    public function obtenerDatosPersonales(): array {
        return  $this->data['cliente']['riesgonet']['individualizacion'];
    }
}