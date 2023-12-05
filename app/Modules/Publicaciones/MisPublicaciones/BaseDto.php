<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Modules\Publicaciones\MisPublicaciones;

use App\Modules\Shared\Dtos\DataTransferObject;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Description of Base
 *
 * @author kodear
 */
class BaseDto extends DataTransferObject {

    public  array  $multimedia = [];
    public  int    $portada_index = 0;
    
    public function obtenerArchivosExistentes(): array {
        return array_filter($this->multimedia, function($archivo) {
            return is_array($archivo);
        });
    }
    
    public function obtenerArchivosNuevos(): array {
        return array_filter($this->multimedia, function($archivo) {
            return $archivo instanceof UploadedFile;
        });
    }
}
