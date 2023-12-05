<?php

namespace App\Modules\Agencias;

use App\Http\Controllers\Controller;
use App\Modules\Agencias\Imagenes\ImagenesBusiness;
use App\Modules\Hubspot\HubspotBusiness;
use App\Modules\Onboarding\Resources\BusinessResource;
use Illuminate\Http\Request;

class AgenciasController extends Controller
{
     public function obtener(Request $request) {
         $nombreAgencia = $request->get('nombre_agencia');
         $agencia = AgenciasBusiness::obtener($nombreAgencia);
         return new BusinessResource($agencia);
    }
    
    public function actualizar(Request $request){
         $usuario = $this->getUser();
         $eliminarPortada = false;
         $eliminarMiniPortada = false;
         
         //Actualizar imagenes
         $portada = $request->file('portada');
         $miniPortada = $request->file('mini_portada');

         if($request->get('portada') == 'eliminar'){
             $eliminarPortada = true;
         }
         if($request->get('mini_portada') == 'eliminar'){
              $eliminarMiniPortada = true;
         }

        ImagenesBusiness::actualizarMiniPortada($usuario, $miniPortada, $eliminarMiniPortada);
        ImagenesBusiness::actualizarPortada($usuario, $portada, $eliminarPortada);


         //Actualizar redes sociales
         $facebook = $request->get('facebook');
         $instagram = $request->get('instagram');
         HubspotBusiness::actualizarRedes($usuario, $facebook, $instagram);

         return $this->json([]);
    }
}
