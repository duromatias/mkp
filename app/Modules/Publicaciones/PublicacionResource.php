<?php

namespace App\Modules\Publicaciones;

use App\Modules\Publicaciones\Favoritos\Resources\FavoritoResource;
use App\Modules\Publicaciones\Multimedia\MultimediaResource;
use App\Modules\Publicaciones\Ofertas\Businesses\OfertaBusiness;
use App\Modules\Publicaciones\Ofertas\Resources\OfertaResource;
use App\Modules\Subastas\Resources\SubastaResource;
use App\Modules\Users\Models\User;
use App\Modules\Users\Resources\UserResource;
use App\Modules\Vehiculos\Resources\TipoCombustibleResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicacionResource extends JsonResource {

    public function toArray( $request) {
    	/** @var User $currentUser */
    	$currentUser = auth('api')->user();

        return [
        	'id'                  => $this->id,
            'usuario_id'          => $this->usuario_id,
            'usuario'             => new UserResource($this->whenLoaded('usuario')),
            'tipo_combustible_id' => $this->tipo_combustible_id,
            'tipo_combustible'    => new TipoCombustibleResource($this->whenLoaded('tipoCombustible')),
            'multimedia'          => MultimediaResource::collection($this->whenLoaded('multimedia')),
			'subasta_id'		  => $this->subasta_id,
			'subasta'			  => new SubastaResource($this->whenLoaded('subasta')),
			'ofertas_propias'	  => OfertaResource::collection($this->whenLoaded('ofertasPropias')),
			'ofertas_ultima_oferta' => new OfertaResource($this->whenLoaded('ultimaOferta')),
			'ofertas_ultima_oferta_propia'	=>  new OfertaResource($this->whenLoaded('ultimaOfertaPropia')),
            'ofertas_valor_incremento' => OfertaBusiness::obtenerValorIncrementoPorPublicacion($this->resource),
			'precio_base'		  => number_format($this->precio_base, 0, '', '.'),
            'brand_id'            => $this->brand_id,
            'codia'               => $this->codia,
            'marca'               => $this->marca,
            'modelo'              => $this->modelo,
            'año'                 => $this->año,
            'color'               => $this->color,
            'condicion'           => $this->condicion,
            'kilometros'          => $this->kilometros,
            'puertas'             => $this->puertas,
            'descripcion'         => $this->descripcion,
            'moneda'              => $this->moneda,
            'precio'              => $this->precio,
            'precio_sugerido'     => $this->precio_sugerido,
            'calle'               => $this->calle,
            'numero'              => $this->numero,
            'localidad'           => $this->localidad,
            'provincia'           => $this->provincia,
            'codigo_postal'       => $this->codigo_postal,
            'latitud'             => $this->latitud,
            'longitud'            => $this->longitud,
            'estado'              => $this->estado,
            'created_at'          => $this->created_at,
            'updated_at'          => $this->updated_at,
            'es_oportunidad'      => $this->es_oportunidad,
			'dominio'			  => $this->dominio,
			'financiacion'		  => $this->financiacion,
			'favorito'			  => new FavoritoResource($this->whenLoaded('favorito')),
            'direccionCompleta'   => $this->resource->obtenerDireccionCompleta(),
            'direccionParcial'    => $this->resource->obtenerDireccionParcial(),
            'telefonoContacto'    => $this->resource->usuario->obtenerTelefonoContacto(),
            'nombreVendedor'      => $this->resource->usuario->obtenerNombreVendedor(),
			'clicks'			  => $this->when($currentUser && $currentUser->esAdministrador(), $this->clicks),
            
            'venta_realizada'         => $this->venta_realizada,
            'compra_realizada'        => $this->compra_realizada,
            'observaciones_vendedor'  => $this->observaciones_vendedor,
            'observaciones_comprador' => $this->observaciones_comprador,
            'calificacion_comprador'  => $this->calificacion_comprador,
            'calificacion_vendedor'   => $this->calificacion_vendedor,
        ];
    }
}
