<?php

namespace App\Modules\Users\Models;

use App\Modules\Auth\Models\Acceso;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
	const ADMINISTRADOR = 1;
	const USUARIO_AGENCIA = 2;
	const USUARIO_PARTICULAR = 3;
	const USUARIO_SIN_LOGIN = 4;

    use HasFactory;

    protected $table = 'roles';

    public function accesos() {
    	return $this->belongsToMany(Acceso::class, 'accesos_roles');
	}
}
