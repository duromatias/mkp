<?php

namespace App\Modules\Prendarios\Vehiculos;

use App\Modules\Onboarding\Auth\AuthBusiness;
use App\Modules\Prendarios\Clients\PrendariosClient;
use App\Modules\Users\Models\User;
use App\Modules\Vehiculos\Business\VehiculosBusiness as MkpVehiculosBusiness;

class VehiculosBusiness {
    
    static private function crearCliente(string $token): PrendariosClient {
        static $client = null;
        if ($client === null) {
            $client = new PrendariosClient();
            $client->login($token);
        }
        
        return $client;
    }
    
    static public function crear(User $user, int $codia, int $anio, int $kilometros, ?string $dominio = null, array $fotos = []) {
        
        $token = static::obtenerTokenOnboarding($user);
        
        $datosModelo = MkpVehiculosBusiness::getOneModelo($codia);
        $descripcion = "{$datosModelo->brand->name} {$datosModelo->description}";
        $precio      = MkpVehiculosBusiness::getPrecioModelo($codia, $anio);

        $response = static::crearCliente($token)->post('/mis-vehiculos', [
            'Desc'   => $descripcion,
            'Domain' => $dominio ?? 'aaa111',
            'Gama'   => $datosModelo->group->id,
            'Km'     => $kilometros,
            'Marca'  => $datosModelo->brand->id,
            'Modelo' => $codia,
            'Photos' => static::genrerarFormatoImagenesParaS1($fotos),
            'Price'  => $precio,
            'Year'   => $anio,
        ]);

        return $response;
    }
    
    static public function genrerarFormatoImagenesParaS1(array $archivos): array {
        
        $fotos = [];
        
        foreach($archivos as $rutaArchivo) {
            $mime    = mime_content_type($rutaArchivo);
            $content = file_get_contents($rutaArchivo);    
            $data    = "data:{$mime};base64," . base64_encode($content);
            $fotos[] = [
                'Active'      => true,
                'Base64'      => $data,
                'Orientation' => 'FRONT',
                'Path'        => null,
            ];
        }
        
        return $fotos;
    }
    
    static public function obtenerTokenOnboarding(User $user) {
        return AuthBusiness::obtenerTokenOnboarding($user);
    }
}
