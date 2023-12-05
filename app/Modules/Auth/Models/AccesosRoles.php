<?php

namespace App\Modules\Auth\Models;

use App\Base\Repository\ModelRepository;

class AccesosRoles extends ModelRepository {
    
    public $timestamps = false;
    protected $guarded = [];

    static public function filtrosEq(): array {
        return ['id', 'rol_id', 'acceso_id'];
    }
    
    static public function agregar($rolId, $accesoId): self {
        $row = new static();
        
        $row->rol_id    = $rolId;
        $row->acceso_id = $accesoId;
        
        $row->guardar();
        
        return $row;
    }
}
