<?php

namespace App\Modules\Direcciones;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Prendarios\Resources\LocalidadResource;


class DireccionesController extends Controller
{
	public function listarLocalidades() {
        $localidades = DireccionesBusiness::listarLocalidades(
            $this->input('page' ,     0),
            $this->input('limit'  ,  10),
            $this->input('filtros',  []),
            $this->input('ordenes',  []),
            $this->input('opciones', []),
        );

        return LocalidadResource::collection($localidades);
    }
    
    public function listarProvincias() {
        $provincias = DireccionesBusiness::listarProvincias();
        return $this->json($provincias);
    }
}