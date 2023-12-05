<?php

namespace App\Modules\Publicaciones\Consultas;

use App\Base\Repository\ModelRepository;
use App\Modules\Onboarding\OnboardingRepository;
use App\Modules\Publicaciones\Publicacion;
use App\Modules\Users\Models\User;
use Illuminate\Database\Eloquent\Builder;

class Consulta extends ModelRepository
{
	const ESTADO_PENDIENTE = 'Pendiente';
	const ESTADO_RESUELTA = 'Resuelta';

	const APTO_CREDITO_SI = 'Si';
	const APTO_CREDITO_NO = 'No';
	const APTO_CREDITO_NO_APLICA = 'No Aplica';
	const APTO_CREDITO_SIN_INFORMACION = 'Sin información';

	protected $guarded = [];

	public function publicacion() {
		return $this->belongsTo(Publicacion::class, 'publicacion_id');
	}

	public function usuarioOrigen() {
		return $this->belongsTo(User::class, 'usuario_origen_id');
	}

	public function usuarioDestino() {
		return $this->belongsTo(User::class, 'usuario_destino_id');
	}

	public static function crearConsulta(int $publicacionId, ?int $usuarioOrigenId, int $usuarioDestinoId, string $nombre, string $email, int $telefono, string $texto) {
        
		$consulta                     = new static;
        $consulta->publicacion_id     = $publicacionId;
        $consulta->usuario_origen_id  = $usuarioOrigenId;
        $consulta->usuario_destino_id = $usuarioDestinoId;
        $consulta->nombre             = $nombre;
        $consulta->email              = $email;
        $consulta->telefono           = $telefono;
        $consulta->texto              = $texto;
        $consulta->apto_credito       = static::APTO_CREDITO_SIN_INFORMACION;
        $consulta->estado             = static::ESTADO_PENDIENTE;

        return $consulta->insertar();
	}

	public function resolver() {
		$this->estado = static::ESTADO_RESUELTA;

		return $this->guardar();
	}
    
    public function marcarPendiente(): self {
		$this->estado = static::ESTADO_PENDIENTE;

		return $this->guardar();
    }
    
    public function guardarRespuesta(string $texto): self {
        $this->respuesta = $texto;

		return $this->guardar();
    }

	public static function aplicarFiltros(Builder $query, array $filtros) {
		foreach($filtros as $nombre => $valor) {
			// Consultas filtros
			if (in_array($nombre, ['id', 'publicacion_id', 'usuario_destino_id', 'apto_credito', 'estado'])) {
				$query->where("consultas.{$nombre}", $valor);
			}

			if (in_array($nombre, ['nombre'])) {
				$query->where("consultas.{$nombre}", 'LIKE', "%{$valor}%");
			}

			// Onbiarding Users filtros
			if (in_array($nombre, ['business_id'])) {
				$query->where("onboardingUsers.{$nombre}", $valor);
			}
		}
	}

	static public function aplicarOrdenes(Builder $query, array $ordenes) {
		parent::aplicarOrdenes($query, $ordenes);

		foreach ($ordenes as $columna => $sentido) {
			if (in_array($columna, ['created_at'])) {
				$query->orderBy($columna, $sentido);
			}
		}
	}

	public static function generarConsulta(array $filtros = [], array $ordenes = [], array $opciones = []): Builder {
		$query = parent::generarConsulta($filtros, $ordenes, $opciones);

		$onboardingDbName = (new OnboardingRepository())->getDatabaseName();

		return $query->select('consultas.*')
			->from('consultas')
			->join('usuarios as usuariosDestino', 'usuariosDestino.id', '=', 'consultas.usuario_destino_id')
			->leftJoin("{$onboardingDbName}.users as onboardingUsers", 'onboardingUsers.id', '=', 'usuariosDestino.onboarding_user_id');
	}
}
