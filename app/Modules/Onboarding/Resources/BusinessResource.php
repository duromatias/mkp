<?php

namespace App\Modules\Onboarding\Resources;

use App\Modules\Agencias\AgenciasBusiness;
use App\Modules\Agencias\Imagenes\ImagenesBusiness;
use App\Modules\Hubspot\HubspotBusiness;
use Illuminate\Http\Resources\Json\JsonResource;

class BusinessResource extends JsonResource {

    public function toArray($request) {
        $redesSociales = [];
        $opciones = $request->get('opciones', []);
        if(!empty($opciones['business.redes_sociales'])){
            $redesSociales = HubspotBusiness::obtenerRedes($this->code);
        }

        return [
            'id'             => $this->id,
            'code'           => $this->code,
            'name'           => $this->name,
            'cuit'           => $this->cuit,
            'phone'          => $this->phone,
			'marketplace_phone' => $this->marketplace_phone,
            'formattedPhone' => $this->formattedPhone,
            'address'        => new AddressResource($this->whenLoaded('address')),
            'ready_at'       => $this->ready_at,
            'rutaImagenPortada'     => ImagenesBusiness::obtenerUrlPortada($this->code),
            'rutaImagenMiniPortada' => ImagenesBusiness::obtenerUrlMiniPortada($this->code),
            'redes_sociales'        => $redesSociales,
        ];
    }
}
