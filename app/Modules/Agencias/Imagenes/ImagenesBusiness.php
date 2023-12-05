<?php

namespace App\Modules\Agencias\Imagenes;

use App\Modules\Onboarding\Models\Business;
use App\Modules\Users\Models\User;
use Gumlet\ImageResize;
use Illuminate\Http\UploadedFile;use Kodear\Laravel\ModelStorage\ModelStorage;
use Kodear\Laravel\ModelStorage\ModelStorageFactory;

use function app;

class ImagenesBusiness {
    
    static public function actualizarMiniPortada(User $usuario, ?UploadedFile $miniPortada, bool $eliminar){
        $businessCode = $usuario->onboardingUser->business->code;

        if( isset($miniPortada) ) {
            static::guardarImagen($businessCode, $miniPortada, 'mini_portada');
        }

        if( $eliminar){
            static::borrarImagen($businessCode, 'mini_portada');
        }
    }

    static public function actualizarPortada(User $usuario, ?UploadedFile $portada, bool $eliminar){
        $businessCode = $usuario->onboardingUser->business->code;

        if( isset($portada) ){
            static::guardarImagen($businessCode, $portada,     'portada');

        }
        if ( $eliminar){
            static::borrarImagen($businessCode, 'portada');
        }
    }
    
    static public function obtenerUrlMiniPortada(int $code) {
        return static::obtenerUrlImagen($code, 'mini_portada');
    }
    
    static public function obtenerUrlPortada(int $code) {
        return static::obtenerUrlImagen($code, 'portada');
    }

    static private function obtenerStorage(string $sufijo): ModelStorage {
        return app()->make(ModelStorageFactory::class)->createFromModel(new Business, "{$sufijo}.png");
    }
    
    static private function guardarImagen(int $id, UploadedFile $imagen, string $sufijo) {
        $storage = static::obtenerStorage($sufijo);
        $storage->storeUploadedFile($id, $imagen);
        $rutaArchivo = $storage->getFullPath($id);
        static::convertirEnPng($rutaArchivo);
    }
    
    static private function borrarImagen(int $id, string $sufijo) {
        $storage = static::obtenerStorage($sufijo);
        try {
            $storage->removeFile($id);
        } catch (\Exception $e) {
            
        }
    }
    
    static private function convertirEnPng(string $rutaArchivo) {
        $imagen = new ImageResize($rutaArchivo);
        $imagen->save($rutaArchivo, IMAGETYPE_PNG);
    }
    
    static private function obtenerUrlImagen(int $id, string $sufijo) {
        return static::obtenerStorage($sufijo)->getUrl($id);
    }
}
