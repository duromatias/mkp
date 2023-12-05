<?php

namespace App\Modules\Publicaciones\Multimedia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MultimediaController extends Controller {
    
    public function index(Request $request, $publicacion_id) {
        $usuarioId = $this->getUserid();
        $rs = MultimediaBusiness::listarPorPublicacion(
            $usuarioId,
            $request->get('offset'  ,  0),
            $request->get('limit'   , 10),
            $request->get('filtros' , []),
            $request->get('ordenes' , []),
            $request->get('opciones', []),
        );
        
        return PublicacionResource::collection($rs);
    }

}