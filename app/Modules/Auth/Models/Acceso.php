<?php

namespace App\Modules\Auth\Models;

use App\Base\Repository\ModelRepository;
use App\Modules\Users\Models\Rol;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Acceso extends ModelRepository
{
    use HasFactory;
    
    public $timestamps = false;

    public function roles() {
    	return $this->belongsTo(Rol::class);
	}
    
    static public function agregar($descripcion, $ruta, $icono, $orden, $grupo): self {
        $row = new static();
        
        $row->descripcion = $descripcion;
        $row->ruta        = $ruta;
        $row->icono       = $icono;
        $row->orden       = $orden;
        $row->grupo 	  = $grupo;

        $row->guardar();
        
        return $row;
    }

    static public function filtrosEq(): array {
        return ['id', 'descripcion'];
    }
}
