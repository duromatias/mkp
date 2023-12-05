<?php

namespace App\Modules\Publicaciones\Multimedia;

use App\Modules\Shared\Exceptions\BusinessException;
use Gumlet\ImageResize;
use Kodear\Laravel\ModelStorage\ModelStorage;
use Kodear\Laravel\ModelStorage\ModelStorageFactory;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MultimediaBusiness {

    static public function getStorage(string $extension, string $sufijo = 'default'): ModelStorage {
        return app()->make(ModelStorageFactory::class)->createFromModel(new Multimedia, "{$sufijo}{$extension}");
    }
    
    static public function agregarArchivos(int $publicacionId, array $archivos): void {
        foreach($archivos as $i => $archivo) {
            $row = static::agregar($publicacionId, $archivo);
        }
    }
    
    static public function agregar(int $publicacionId, UploadedFile $archivo): Multimedia {
        $tipo      = static::obtenerTipo($archivo);
        $extension = static::obtenerExtensionDesdeArchivo($archivo);
        $row       = Multimedia::crear($publicacionId, $tipo, $extension);
        $storage   = static::getStorage($extension);
        $storage->storeUploadedFile($row->id, $archivo);
        
        if (static::esImagen($archivo)) {
            $storageCard = static::getStorage($extension, 'card');
            $cardFilePath = $storageCard->getFullPath($row->id);
            $storageCard->storeUploadedFile($row->id, $archivo);
            static::redimensionar($cardFilePath, 348, 194);
        }
        
        return $row;
    }
    
    static public function actualizarPortada(int $publicacionId, int $portada_index) {
        $rs = Multimedia::listarTodos([
            'publicacion_id' => $publicacionId,
            'estado'         => Multimedia::ESTADO_VISIBLE,
            'tipo'           => Multimedia::TIPO_IMAGE,
        ], [
            'id'             => 'asc',
        ]);
        
        foreach($rs as $i => $row) {
            if ($i === $portada_index) {
                static::marcarPortada($row->id);
                break;
            }
        }
    }
    
    static public function marcarPortada($multimedia_id) {
        $multimedia = Multimedia::getById($multimedia_id);
        $rs = Multimedia::listarTodos([
            'publicacion_id' => $multimedia->publicacion_id,
            'es_portada'     => 'SI',
        ]);
        foreach($rs as $row) {
            $row->desmarcarEsPortada();
        }
        
        $multimedia = Multimedia::getById($multimedia_id);
        $multimedia->marcarEsPortada();
    }
    
    static public function obtenerTipo(UploadedFile $archivo) {
        $mimeType = $archivo->getMimeType();
        list($type, $subtype) = explode('/', $mimeType);
        
        return $type;
    }
    
    static public function obtenerExtensionDesdeArchivo(UploadedFile $archivo): string {
        $mimeType = $archivo->getMimeType();

        $map = [
            'image/png'       => '.png',
            'image/jpg'       => '.jpg',
            'image/jpeg'      => '.jpg',
            'image/gif'       => '.gif',
            'image/webp'      => '.webp',
            'video/webm'      => '.webm',
            'video/mp4'       => '.mp4',
            'video/mpeg'      => '.mpg',
            'video/mpg'       => '.mpg',
            'video/3gpp'      => '.3gp',
            'video/x-msvideo' => '.avi',
            'video/wav'       => '.wav',
            'video/ogg'       => '.ogg', 
            'video/quicktime' => '.mov', 
        ];
        
        if (!isset($map[$mimeType])) {
            throw new BusinessException('No se pudo determinar la extensión');
        }
        
        return $map[$mimeType];
    }
    
    static public function mimeTypePermitido(UploadedFile $archivo): bool {
        $mimeType = $archivo->getMimeType();
        list($type, $subtype) = explode('/', $mimeType);
        if (!in_array($type, ['image', 'video'])) {
            return false;
        }
        
        if (in_array($subtype, ['webp', 'webm'])) {
            return false;
        }
        return true;
    }
    
    static public function obtenerTamañoMaximoVideos(): int {
        return 1024 * 1024 * 10;
    }
    
    static private function esImagen(UploadedFile $archivo): bool {
        return static::obtenerTipo($archivo) === 'image';
    }
    
    static public function sincronizar($publicacionId, array $archivos): void {
        $archivosId = array_map(function($row) { return $row['id']; }, $archivos);
        
        $rs = Multimedia::listarTodos([
            'publicacion_id' => $publicacionId,
        ]);
        
        foreach($rs as $row) {
            if (!in_array($row->id, $archivosId)) {
                $row->marcarEliminado();
            }
        }
    }
    
    static public function getUrlImagen(int $id, string $extension, string $sufijo = 'default') {
        return static::getStorage($extension, $sufijo)->getUrl($id);
    }
    
    static public function getFullPath(int $id, string $extension, string $sufijo = 'default') {
        return static::getStorage($extension, $sufijo)->getFullPath($id);
    }
    
    static public function obtenerCantidadFotos(int $publicacionId) {
        return Multimedia::contar([
            'publicacion_id' => $publicacionId,
            'estado'         => Multimedia::ESTADO_VISIBLE,
            'tipo'           => Multimedia::TIPO_IMAGE,
        ]);
    }
    
    static public function obtenerCantidadVideos(int $publicacionId) {
        return Multimedia::contar([
            'publicacion_id' => $publicacionId,
            'estado'         => Multimedia::ESTADO_VISIBLE,
            'tipo'           => Multimedia::TIPO_VIDEO,
        ]);
    }
    
    static public function redimensionar($rutaArchivo, $width, $height) {
        $image = new ImageResize($rutaArchivo);
        $image->resizeToWidth($width, true);
        if ($image->getDestHeight()< $height) {
            $image->resizeToHeight($height, true);
        }
        $image->crop($width, $height);
        $image->save($rutaArchivo);
    }
}