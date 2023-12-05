<?php

namespace App\Modules\Publicaciones\Multimedia;

use App\Base\Repository\ModelRepository;
use Illuminate\Database\Eloquent\Builder;

class Multimedia extends ModelRepository {

	protected $table = 'publicaciones_multimedia';
    
    const ESTADO_VISIBLE   = 'VISIBLE';
    const ESTADO_ELIMINADO = 'ELIMINADO';
    
    const TIPO_IMAGE       = 'image';
    const TIPO_VIDEO       = 'video';
    
    const ES_PORTADA_SI    = 'SI';
    const ES_PORTADA_NO    = 'NO';

	public static function crear(int $publicacionId, string $tipo, string $extension) {
		$row = new static();
        
        $row->publicacion_id = $publicacionId;
        $row->tipo           = $tipo;
        $row->extension      = $extension;
        $row->es_portada     = static::ES_PORTADA_NO;
        $row->estado         = static::ESTADO_VISIBLE;

        return $row->guardar();
	}
    
    static public function aplicarFiltros(Builder $query, array $filtros) {
        
        foreach($filtros as $nombre => $valor) {
            
            if (in_array($nombre, ['id', 'publicacion_id', 'es_portada', 'tipo', 'estado'])) {
                $valor = is_array($valor) ? $valor : [$valor];
                $query->whereIn($nombre, $valor);
            }
        }
    }
    
    static public function aplicarOrdenes(Builder $query, array $ordenes) {
        parent::aplicarOrdenes($query, $ordenes);
        
        foreach($ordenes as $nombre => $valor) {
            if (in_array($nombre, ['id', 'es_portada', 'created_at', 'updated_at'])) {
                $query->orderBy($nombre, $valor);
            }
        }
    }
    
    public function marcarEsPortada(): self {
        $this->es_portada = 'SI';
        return $this->guardar();
    }
    
    public function desmarcarEsPortada(): self {
        $this->es_portada = 'NO';
        return $this->guardar();
    }
    
    public function marcarEliminado(): self {
        $this->estado = static::ESTADO_ELIMINADO;
        return $this->guardar();
    }
    
    public function esImagen(): bool {
        return $this->tipo === static::TIPO_IMAGE;
    }
}
