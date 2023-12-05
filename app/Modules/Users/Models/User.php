<?php

namespace App\Modules\Users\Models;

use App\Base\BusinessException;
use App\Modules\Auth\Dtos\RegistrarAgenciaDto;
use App\Modules\Auth\Dtos\RegistrarParticularDto;
use App\Modules\Auth\Notifications\PasswordReset;
use App\Modules\Direcciones\ModeloConLocalizacion;
use App\Modules\Onboarding\Models\OnboardingUser;
use App\Modules\Onboarding\OnboardingRepository;
use App\Modules\Shared\Repositories\ModelRepository;
use App\Modules\Users\Dtos\ActualizarUsuarioDto;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use function config;
use function in_array;

class User extends ModelRepository implements CanResetPassword
{
    use HasApiTokens;
	use Notifiable;
	use Authenticatable;
	use Authorizable;
    use ModeloConLocalizacion;


	const HABILITADO = 'HABILITADO';
    const DESHABILITADO = 'DESHABILITADO';

	const SEXO_MASCULINO = 'M';
	const SEXO_FEMENINO = 'F';

	const SEXOS = [
		self::SEXO_MASCULINO,
		self::SEXO_FEMENINO
	];
	const ESTADO_CIVIL_SOLTERO = '1';
	const ESTADO_CIVIL_CASADO = '2';
	const ESTADO_CIVIL_DIVORCIADO = '3';
	const ESTADO_CIVIL_VIUDO = '4';

	const ESTADOS_CIVIL = [
		self::ESTADO_CIVIL_SOLTERO,
		self::ESTADO_CIVIL_CASADO,
		self::ESTADO_CIVIL_DIVORCIADO,
		self::ESTADO_CIVIL_VIUDO
	];

	const USO_VEHICULO_PARTICULAR = '1';
	const USO_VEHICULO_REMIS = '2';

	const USOS_VEHICULO = [
		self::USO_VEHICULO_PARTICULAR,
		self::USO_VEHICULO_REMIS
	];

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
		'apellido',
        'email',
        'password',
		'rol_id',
		'estado',
		'sexo',
		'estado_civil_id',
		'uso_vehiculo',
		'dni',
		'telefono',
		'calle',
		'numero',
		'localidad',
		'provincia',
		'codigo_postal',
		'latitud',
		'longitud',
        'fecha_nacimiento',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];


    public function rol() {
    	return $this->belongsTo(Rol::class, 'rol_id');
	}

	public function onboardingUser() {
    	return $this->belongsTo(OnboardingUser::class, 'onboarding_user_id');
	}


	static public function crearParticular(RegistrarParticularDto $registrarParticularDto): self {
		$user = new static;
		$user->email = $registrarParticularDto->email;
		$user->nombre = $registrarParticularDto->nombre;
		$user->password = Hash::make($registrarParticularDto->password);
		$user->rol_id = Rol::USUARIO_PARTICULAR;
		$user->estado = User::HABILITADO;

		$user->insertar();

		return $user;
	}


	static public function crearAdministrador(string $email, string $nombre, string $password): self {
		$user           = new static;
        $user->email    = $email;
        $user->nombre   = $nombre;
        $user->password = Hash::make($password);
        $user->rol_id   = 1;
        $user->estado   = User::HABILITADO;

        $user->insertar();

		return $user;
	}

	static public function crearAgencia(RegistrarAgenciaDto $registrarAgenciaDto) : self {
		$user = new static;
		$user->email = $registrarAgenciaDto->email;
		$user->rol_id = Rol::USUARIO_AGENCIA;
		$user->onboarding_user_id = $registrarAgenciaDto->onboarding_user_id;
		$user->estado = User::HABILITADO;

		$user->insertar();

		return $user;
	}
    
    public function actualizarPassword(string $password): self {
        $this->password = Hash::make($password);
        $this->guardar();

        return $this;
    }

    public function habilitar(): self {
		$this->estado = static::HABILITADO;
		$this->guardar();

		return $this;
    }

    public function deshabilitar(): self {
		$this->estado = static::DESHABILITADO;
		$this->guardar();

		return $this;
    }

    static public function generarConsulta(array $filtros = [], array $ordenes = [], array $opciones = []): Builder {
        $query = parent::generarConsulta($filtros, $ordenes, $opciones);

        $query->selectRaw("
            u.id,
            u.email,
            u.password,
            u.nombre,
            u.apellido,
            u.telefono,
            u.dni,
            u.codigo_postal,
            u.sexo,
            u.estado_civil_id,
            u.fecha_nacimiento,
            u.uso_vehiculo,
            u.rol_id,
            u.remember_token,
            u.created_at,
            u.updated_at,
            u.estado,
            u.onboarding_user_id,
            IF(u.rol_id = 2, a.street,      u.calle         ) AS calle,
            IF(u.rol_id = 2, a.number,      u.numero        ) AS numero,
            IF(u.rol_id = 2, a.postal_code, u.codigo_postal ) AS codigo_postal,
            IF(u.rol_id = 2, a.locality,    u.localidad     ) AS localidad,
            IF(u.rol_id = 2, ap.name,       u.provincia     ) AS provincia,
            IF(u.rol_id = 2, a.latitude,    u.latitud       ) AS latitud,
            IF(u.rol_id = 2, a.longitude,   u.longitud      ) AS longitud
        ");
        $query->from("usuarios AS u");
        $onboardingDbName = (new OnboardingRepository())->getDatabaseName();
        $query->leftJoin("{$onboardingDbName}.users              AS u2" , 'u2.id',            '=', 'u.onboarding_user_id');
        $query->leftJoin("{$onboardingDbName}.user_personal_data AS upd", 'upd.user_id',      '=', 'u2.id'               );
        $query->leftJoin("{$onboardingDbName}.businesses         AS b"  , 'b.id',             '=', 'u2.business_id'      );
		$query->leftJoin("{$onboardingDbName}.addresses          AS a"  , 'a.addressable_id', '=', 'b.id'                );
		$query->leftJoin("{$onboardingDbName}.provinces          AS ap" , 'ap.id',            '=', 'a.province_id'       );


		return $query;
    }
    
    public function actualizarDatosPersonales(ActualizarUsuarioDto $dto): self {        
        $this->nombre        = $dto->nombre;
        $this->dni           = $dto->dni;
        $this->telefono      = $dto->telefono;
        $this->calle         = $dto->calle;
        $this->numero        = $dto->numero;
        $this->codigo_postal = $dto->codigo_postal;
        $this->localidad     = $dto->localidad;
        $this->provincia     = $dto->provincia;
        $this->latitud       = $dto->latitud;
        $this->longitud      = $dto->longitud;
        $this->guardar();
        
        return $this;
    }

    /**
     * NO BORRAR NI MODIFICAR ESTE MÉTODO.
     * Dejarlo así.
     */
    static public function filtrosEq(): array {
        return [];
    }

    static public function aplicarFiltros(Builder $query, array $filtros) {
		parent::aplicarFiltros($query, $filtros);


		foreach($filtros as $nombre => $valor) {
			/* Users table filters */
			if (in_array($nombre, ['id', 'estado'])) {
				$query->where("u.{$nombre}", $valor);
			}

			if (in_array($nombre, ['email'])) {
				$query->where("u.{$nombre}", '=', $valor);
			}

			if (in_array($nombre, ['email_like'])) {
				$query->where("u.email", 'LIKE', "%{$valor}%");
			}

			if ($nombre === 'dni') {
                $query->whereRaw("
                    (
                        u.dni LIKE '%{$valor}%' OR
                        b.cuit LIKE '%{$valor}%'
                    )
                ");
			}

			if (in_array($nombre, ['rol_id'])) {
				$valor = explode(',', $valor);
				$query->whereIn("u.{$nombre}", $valor);
			}

			// Onbarding Business table filters
			if (in_array($nombre, ['onboarding_business_name'])) {
				$query->where('b.name', 'LIKE', "%{$valor}%");
			}


			// Onboarding User Personal Data table filters
           	if (in_array($nombre, ['onboarding_user_name'])) {
                $query->whereRaw("CONCAT(upd.first_name, ' ', upd.last_name) LIKE '%{$valor}%'");
            }


           	// OrWhere filters
			if ($nombre === 'nombre') {
                $query->whereRaw("
                    (
                        u.nombre  LIKE '%{$valor}%' OR
                        CONCAT(upd.first_name, ' ', upd.last_name) LIKE '%{$valor}%'
                    )
                ");
			}
		}
	}

    static public function aplicarOrdenes(Builder $query, array $ordenes) {
        parent::aplicarOrdenes($query, $ordenes);

        foreach ($ordenes as $columna => $sentido) {
            if (in_array($columna, ['created_at',  'estado'])) {
                $query->orderBy($columna, $sentido);
            }
        }
    }
    
    static public function getByEmail(string $email): self {
        return static::getOne(['email' => $email]);
    }

	public function getEmailForPasswordReset() {
		return $this->email;
	}

	public function sendPasswordResetNotification($token) {
		$this->notify(new PasswordReset($token));
    }

	public function esAgencia() {
    	return $this->rol_id === Rol::USUARIO_AGENCIA;
	}

	public function esAgenciaReady() {
		return $this->onboardingUser &&
			$this->onboardingUser->business &&
			$this->onboardingUser->business->ready_at;
	}

	public function esParticular() {
		return $this->rol_id === Rol::USUARIO_PARTICULAR;
	}

	public function esAdministrador() {
		return $this->rol_id === Rol::ADMINISTRADOR;
	}

	public function direccionCargada() {
    	return $this->latitud !== null;
	}

	public function actualizarDireccion(string $calle, string $numero, string $codigo_postal,
		string $localidad, string $provincia, float $latitud, float $longitud
	) {
		$this->calle = $calle;
		$this->numero = $numero;
		$this->codigo_postal = $codigo_postal;
		$this->localidad = $localidad;
		$this->provincia = $provincia;
		$this->latitud = $latitud;
		$this->longitud = $longitud;

		$this->guardar();
	}

	public function telefonoCargado() {
    	if ($this->esAgencia()) {
			return $this->onboardingUser && $this->onboardingUser->business && $this->onboardingUser->business->phone !== null;
		}
		else if ($this->esAdministrador() || $this->esParticular()) {
			return $this->telefono !== null;
		}
	}
    
    public function obtenerDireccionVendedor(): string {
        if ($this->esAgencia()) {
            return $this->obtenerDireccionAgencia();
        }
        if ($this->esParticular()) {
            return $this->obtenerDireccionCompleta();
        }
        return '';
    }

    private function obtenerDireccionAgencia(): string {
        if (!$this->onboardingUser) {
            return '';
        }
        
        if (!$this->onboardingUser->business) {
            return '';
        }
        
        if (!$this->onboardingUser->business->address) {
            return '';
        }
        
        return $this->onboardingUser->business->address->obtenerDireccionCompleta();
    }
    
    public function obtenerTelefonoContacto(): string {
        if ($this->esAgencia()) {
            if (!$this->onboardingUser) {
                return '';
            }
            
            if (!$this->onboardingUser->business) {
                return '';
            }
            
            return $this->onboardingUser->business->formattedPhone;
        }
        
        if ($this->esParticular()) {
            return "549{$this->telefono}";
        }
        
        return '';
    }
    
    public function obtenerNombreVendedor(): string {
        if ($this->esAgencia()) {
            if (!$this->onboardingUser) {
                return '';
            }
            
            if (!$this->onboardingUser->business) {
                return '';
            }
            
            return $this->onboardingUser->business->name ?? '';
            
        }
        if ($this->esParticular()) {
            return $this->nombre ?? '';
        }
        return '';
    }
    
    public function obtenerAgenciaId(): int {
        if (!$this->esAgencia()) {
            throw new BusinessException('La publicacion corresponde a un usuario Particular');
        }
        return $this->onboardingUser->business->code;
    }

	public static function getUsersSpaPage() {
		return config('app.spa_url') . '/usuarios';
	}
}
