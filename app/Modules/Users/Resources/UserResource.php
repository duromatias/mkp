<?php

namespace App\Modules\Users\Resources;

use App\Modules\Onboarding\Resources\OnboardingUserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
        	'id'                 => $this->id,
            'onboarding_user_id' => $this->onboarding_user_id,
            'onboarding_user'    => new OnboardingUserResource($this->whenLoaded('onboardingUser')),
            'email'              => $this->email,
            'nombre'             => $this->nombre,
			'apellido'			 => $this->apellido,
            'telefono'           => $this->telefono,
            'calle'              => $this->calle,
            'numero'             => $this->numero,
            'localidad'          => $this->localidad,
            'provincia'          => $this->provincia,
            'codigo_postal'      => $this->codigo_postal,
            'latitud'            => $this->latitud,
            'longitud'           => $this->longitud,
            'dni'                => $this->dni,
			'sexo'				 => $this->sexo,
			'estado_civil_id'	 => $this->estado_civil_id,
			'uso_vehiculo'		 => $this->uso_vehiculo,
			'fecha_nacimiento'	 => $this->fecha_nacimiento,
            'rol_id'             => $this->rol_id,
            'rol'                => new RolResource($this->whenLoaded('rol')),
            'estado'             => $this->estado,
            'direccionCompleta'  => $this->resource->obtenerDireccionCompleta(),
            'telefonoContacto'   => $this->resource->obtenerTelefonoContacto(),
			'created_at'		 => $this->created_at,
        ];
    }
}
