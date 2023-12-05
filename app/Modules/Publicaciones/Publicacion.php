<?php

namespace App\Modules\Publicaciones;

use App\Base\Repository\ModelRepository;
use App\Modules\Direcciones\ModeloConLocalizacion;
use App\Modules\Onboarding\Models\Business;
use App\Modules\Onboarding\OnboardingRepository;
use App\Modules\Parametros\Parametro;
use App\Modules\Publicaciones\Favoritos\Models\Favorito;
use App\Modules\Publicaciones\MisPublicaciones\CrearPublicacionDto;
use App\Modules\Publicaciones\Multimedia\Multimedia;
use App\Modules\Publicaciones\Ofertas\Models\Oferta;
use App\Modules\Subastas\Subasta;
use App\Modules\Users\Models\Rol;
use App\Modules\Users\Models\User;
use App\Modules\Vehiculos\TipoCombustible;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;

class Publicacion extends ModelRepository
{

    use ModeloConLocalizacion;


	public const CONDICION_0KM = '0km';
	public const CONDICION_USADO = 'Usado';

	public const CONDICION_OPCIONES = [
		self::CONDICION_0KM,
		self::CONDICION_USADO
	];

	public const PUERTAS_2 = '2';
	public const PUERTAS_3 = '3';
	public const PUERTAS_4 = '4';
	public const PUERTAS_5 = '5';

	public const PUERTAS_OPCIONES = [
		self::PUERTAS_2,
		self::PUERTAS_3,
		self::PUERTAS_4,
		self::PUERTAS_5
	];

	public const MONEDA_PESOS = 'Pesos';
	public const MONEDA_DOLARES = 'Dólares';

	public const MONEDA_OPCIONES = [
		self::MONEDA_PESOS,
		self::MONEDA_DOLARES
	];

	public const ESTADO_ACTIVA = 'Activa';
	public const ESTADO_VENDIDO = 'Vendido';
	public const ESTADO_ELIMINADA = 'Eliminada';

	public const ESTADO_OPCIONES = [
		self::ESTADO_ACTIVA,
		self::ESTADO_VENDIDO,
		self::ESTADO_ELIMINADA
	];

	public const ORIGEN_DEUSADOS = 'Deusados';
	public const ORIGEN_EXTERNO = 'Externo';
	public const ORIGEN_OPCIONES = [
		self::ORIGEN_DEUSADOS,
		self::ORIGEN_EXTERNO
	];

	protected $table = 'publicaciones';

	protected $guarded = [];

    protected $with = [
        'usuario',
        'tipoCombustible',
        'multimedia',
    ];

	public function usuario(): BelongsTo {
		return $this->belongsTo(User::class, 'usuario_id', 'id');
	}

	public function tipoCombustible(): BelongsTo {
		return $this->belongsTo(TipoCombustible::class, 'tipo_combustible_id');
	}

	public function subasta(): BelongsTo {
		return $this->belongsTo(Subasta::class, 'subasta_id');
	}

	public function ofertas(): HasMany {
		return $this->hasMany(Oferta::class, 'publicacion_id');
	}

    public function multimedia(): HasMany {
        return $this->hasMany(Multimedia::class, 'publicacion_id', 'id')
            ->where('estado', Multimedia::ESTADO_VISIBLE);
    }

    public function favorito(): HasOne {
		$currentUserId = Auth::guard('api')->id();

		return $this->hasOne(Favorito::class, 'publicacion_id', 'id')
			->where('usuario_id', '=', $currentUserId);
	}

	public function ofertasPropias(): HasMany {
		$currentUserId = Auth::guard('api')->id();

		return $this->hasMany(Oferta::class, 'publicacion_id', 'id')
			->where('usuario_id', '=', $currentUserId);
	}

	public function ultimaOfertaPropia(): HasOne {
		$currentUserId = Auth::guard('api')->id();

		return $this->hasOne(Oferta::class, 'publicacion_id', 'id')
			->where('usuario_id', '=', $currentUserId)
			->latest();
	}

	public function ultimaOferta(): HasOne {
		return $this->hasOne(Oferta::class, 'publicacion_id', 'id')->latest();
	}

	public static function crear(CrearPublicacionDto $dto, int $userId, string $marca, string $modelo, float $precioSugerido) {
		$publicacion = new static();

		$publicacion->usuario_id          = $userId;
        $publicacion->marca               = $marca;
        $publicacion->modelo              = $modelo;
        $publicacion->brand_id            = $dto->brand_id;
        $publicacion->codia               = $dto->codia;
        $publicacion->año                 = $dto->año;
        $publicacion->color               = $dto->color;
        $publicacion->condicion           = $dto->condicion;
        $publicacion->kilometros          = $dto->kilometros ?? 0;
        $publicacion->puertas             = $dto->puertas;
        $publicacion->tipo_combustible_id = $dto->tipo_combustible_id;
        $publicacion->descripcion         = $dto->descripcion ?? null;
        $publicacion->moneda              = $dto->moneda;
        $publicacion->precio              = $dto->precio;
        $publicacion->precio_sugerido     = $precioSugerido;
        $publicacion->calle               = $dto->calle;
        $publicacion->numero              = $dto->numero;
        $publicacion->localidad           = $dto->localidad;
        $publicacion->provincia           = $dto->provincia;
        $publicacion->codigo_postal       = $dto->codigo_postal;
        $publicacion->latitud             = $dto->latitud;
        $publicacion->longitud            = $dto->longitud;
		$publicacion->origen			  = $dto->origen;
		$publicacion->subasta_id		  = $dto->subasta_id;
		$publicacion->precio_base		  = $dto->precio_base;
		$publicacion->dominio			  = $dto->dominio;
		$publicacion->estado              = static::ESTADO_ACTIVA;


        return $publicacion->guardar();
	}

    public function actualizar(CrearPublicacionDto $dto, float $precioSugerido): self {
        $this->color               = $dto->color;
        $this->condicion           = $dto->condicion;
        $this->kilometros          = $dto->kilometros ?? 0;
        $this->puertas             = $dto->puertas;
        $this->tipo_combustible_id = $dto->tipo_combustible_id;
        $this->descripcion         = $dto->descripcion ?? null;
        $this->precio              = $dto->precio;
        $this->precio_sugerido     = $precioSugerido;
        $this->calle               = $dto->calle;
        $this->numero              = $dto->numero;
        $this->localidad           = $dto->localidad;
        $this->provincia           = $dto->provincia;
        $this->codigo_postal       = $dto->codigo_postal;
        $this->latitud             = $dto->latitud;
        $this->longitud            = $dto->longitud	;
        $this->subasta_id		   = $dto->subasta_id;
        $this->precio_base		   = $dto->precio_base;
		$this->dominio			   = $dto->dominio;
        return $this->guardar();
    }
    
    public function actualizarVentaRealizada(int $resultado, ?int $oferta_ganadora_id = null) : self{
        $this->venta_realizada    = $resultado;
        $this->oferta_ganadora_id = $oferta_ganadora_id;
        $this->guardar();
        return $this;
    }
    
    public function actualizarCompraRealizada(int $resultado) : self{
        $this->compra_realizada = $resultado;
        $this->guardar();
        return $this;
    }
    
    public function actualizarCalificacionComprador(string $calificacion) : self{
        $this->calificacion_comprador = $calificacion;
        $this->guardar();
        return $this;
    }
    
    public function actualizarCalificacionVendedor(string $calificacion) : self{
        $this->calificacion_vendedor = $calificacion;
        $this->guardar();
        return $this;
    }
    
    public function actualizarObservacionesVendedor(string $observaciones) : self{
        $this->observaciones_vendedor = $observaciones;
        $this->guardar();
        return $this;
    }
    
    public function actualizarObservacionesComprador(string $observaciones) : self{
        $this->observaciones_comprador = $observaciones;
        $this->guardar();
        return $this;
    }

    public function eliminar(string $motivo): void {
        $this->estado = static::ESTADO_ELIMINADA;
        $this->guardar();
    }

    public function contarClick(): void {
		$this->clicks += 1;

        $this->timestamps = false;
		$this->guardar();
        $this->timestamps = true;
	}

    static private function condicionOportunidad() {
        $porcentaje = Parametro::valorPorcentajeOportinidad();
        return "p.precio < p.precio_sugerido * (1 - {$porcentaje} / 100)";
    }

    static public function generarConsultaBase(array $filtros = [], array $ordenes = [], array $opciones = []): Builder {
        $query = parent::generarConsulta($filtros, $ordenes, $opciones);
        $query->from("publicaciones AS p");
        $query->join('tipos_combustible AS tc', 'tc.id', '=', 'p.tipo_combustible_id');
        $query->join('usuarios AS u',           'u.id',  '=', 'p.usuario_id'         );
        $query->join('roles AS ur',             'ur.id', '=', 'u.rol_id'             );

        return $query;

    }

    static public function generarConsulta(array $filtros = [], array $ordenes = [], array $opciones = []): Builder {
        $query = static::generarConsultaBase($filtros, $ordenes, $opciones);
        $oportunidad = static::condicionOportunidad();
        $query->selectRaw("
            p.*,
            IF ({$oportunidad}, 1, 0) AS es_oportunidad
        ");

        return $query;
    }

    static public function aplicarFiltros(Builder $query, array $filtros) {
        // parent::aplicarFiltros($query, $filtros);

        foreach($filtros as $nombre => $valor) {
            if (empty($valor)) {
                continue; // no quitar
            }

            if ($nombre === 'usuario_rol_id') {
                $valor = is_array($valor) ? $valor : [$valor];
                $query->whereIn("ur.id", $valor);
            }

            if ($nombre === 'es_oportunidad') {
                $query->whereRaw(static::condicionOportunidad());
            }

            if ($nombre === 'business_id') {
                static::aplicarFiltroBusinessId($query, $valor);
            }

            if ($nombre === 'business_name') {
                $business = Business::getByName($valor);
                static::aplicarFiltroBusinessId($query, $business->id);
            }

            if ($nombre === 'vigente') {
                $query->where('p.estado', static::ESTADO_ACTIVA);

                $vigenciaParameter = Parametro::getById(6);
                $query->whereRaw("DATE(p.updated_at) >= DATE_ADD(DATE(NOW()), INTERVAL -{$vigenciaParameter->valor} DAY)");
            }

            if (in_array($nombre, [
                'id',
                'usuario_id',
                'provincia',
                'localidad',
                'moneda',
                'marca',
                'modelo',
                'color',
                'puertas',
                'tipo_combustible_id',
				'financiacion'
            ])) {
                $valor = is_array($valor) ? $valor : [$valor];
                $query->whereIn("p.{$nombre}", $valor);
            }

            if ($nombre === 'estado') {
				if ($valor === 'activa_vigente') {
					$query->where('p.estado', static::ESTADO_ACTIVA);

					$vigenciaParameter = Parametro::getById(Parametro::ID_VIGENCIA_PUBLICACIÓN);
					$query->whereRaw("DATE(p.updated_at) >= DATE_ADD(DATE(NOW()), INTERVAL -{$vigenciaParameter->valor} DAY)");
                } elseif ($valor === 'activa_vencida') {
                    $query->where('p.estado', static::ESTADO_ACTIVA);

					$vigenciaParameter = Parametro::getById(Parametro::ID_VIGENCIA_PUBLICACIÓN);
                    $query->whereRaw("DATE(p.updated_at) < DATE_ADD(DATE(NOW()), INTERVAL -{$vigenciaParameter->valor} DAY)");
                } elseIf ($valor = "activa_por_vencer") {
					$query->where('p.estado', static::ESTADO_ACTIVA);

					$porVencerParameter = Parametro::getById(Parametro::ID_PUBLICACION_PROXIMA_VENCER);
					$query->whereRaw("DATE(p.updated_at) < DATE_ADD(DATE(NOW()), INTERVAL -{$porVencerParameter->valor} DAY)");
				}
				else {
                    $valor = is_array($valor) ? $valor : [$valor];
                    $query->whereIn("p.{$nombre}", $valor);
                }
            }

            if ($nombre === 'busqueda_marca_modelo') {
                $query->whereRaw("CONCAT(p.marca, ' ', p.modelo) LIKE '%{$valor}%'");
            }

            if ($nombre === 'busqueda_marca') {
                $query->whereRaw("p.marca LIKE '%{$valor}%'");
            }

            if ($nombre === 'busqueda_modelo') {
                $query->whereRaw("p.modelo LIKE '%{$valor}%'");
            }

            if ($nombre === 'kilometros_desde') {
                $query->whereRaw("p.kilometros >= '{$valor}'");
            }

            if ($nombre === 'kilometros_hasta') {
                $query->whereRaw("p.kilometros <= '{$valor}'");
            }

            if ($nombre === 'año_desde') {
                $query->whereRaw("p.año >= '{$valor}'");
            }

            if ($nombre === 'año_hasta') {
                $query->whereRaw("p.año <= '{$valor}'");
            }

            if ($nombre === 'precio_desde') {
                $query->whereRaw("p.precio >= '{$valor}'");
            }

            if ($nombre === 'precio_hasta') {
                $query->whereRaw("p.precio <= '{$valor}'");
            }

            // Date Publicacion
			if (in_array($nombre, ['updated_at'])) {
				$query->whereDate("p.{$nombre}", '=', $valor);
			}

			// Subastas
			if ($nombre === 'subasta_id') {
				static::join($query, 'subastas as s', 'p.subasta_id', '=', 's.id' );
				$query->where('s.id', '=', $valor);
			}

			if ($nombre === 'sin_subasta') {
				$query->whereNull('p.subasta_id');
			}

			if ($nombre === 'incluir_subasta_id') {
				$query->leftJoin('subastas as ss', 'p.subasta_id', '=', 'ss.id');

				$query->where(function ($query) use ($valor) {
					$query->whereNull('p.subasta_id')
						->orWhere('p.subasta_id', '=', $valor);
				});
			}


			if ($nombre === 'en_subasta' && $valor) {
				static::join($query, 'subastas as s', 'p.subasta_id', '=', 's.id' );
			}

            if ($nombre === 'subasta_estado') {
                static::join($query, 'subastas as s', 'p.subasta_id', '=', 's.id' );

                if ($valor === 'vigente') {
                    $query->whereDate('s.fecha_inicio_ofertas', '<=', date('Y-m-d'));
                    $query->whereDate('s.fecha_fin_ofertas', '>=', date('Y-m-d'));
                }

                if ($valor === 'finalizada') {
                    $query->whereDate('s.fecha_fin_ofertas', '<', date('Y-m-d'));
                }

                if ($valor === 'proxima') {
                    $query->whereDate('s.fecha_inicio_ofertas', '>', date('Y-m-d'));
                }
            }

			if ($nombre === 'subasta_fecha_fin_ofertas_desde') {
				static::join($query, 'subastas as s', 'p.subasta_id', '=', 's.id' );
				$query->whereDate('s.fecha_fin_ofertas', '>=', $valor);
			}

			if ($nombre === 'subasta_fecha_fin_ofertas_hasta') {
				static::join($query, 'subastas as s', 'p.subasta_id', '=', 's.id' );

				$query->whereDate('s.fecha_fin_ofertas', '<=', $valor);
			}

			// Favoritos
			if ($nombre === 'favoritos_usuario_id') {
				static::join($query, 'favoritos', 'p.id', '=', 'favoritos.publicacion_id');

				$query->where('favoritos.usuario_id', '=', $valor);
			}

			// Ofertas
			if ($nombre === 'ofertas_usuario_id') {
				$query->whereIn('p.id', function($query) use($valor) {
					$query->select('publicacion_id')
						->from('ofertas')
						->where('usuario_id', '=', $valor);
				});
			}
        }
    }


    static private function aplicarFiltroBusinessId(Builder $query, $valor) {
        $onboardingDbName = (new OnboardingRepository())->getDatabaseName();
        $query->leftJoin("{$onboardingDbName}.users AS u2" , 'u2.id', '=', 'u.onboarding_user_id');
        $query->where('u2.business_id', $valor);
    }

    static public function aplicarOrdenes(Builder $query, array $ordenes) {
        parent::aplicarOrdenes($query, $ordenes);

        foreach($ordenes as $nombre => $valor) {
            if (in_array($nombre, ['created_at', 'updated_at', 'puertas', 'modelo', 'año', 'moneda', 'precio', 'financiacion'])) {
                $query->orderBy("p.{$nombre}", $valor);
            }

            // Subastas
            if ($nombre == 'subasta_fecha_fin_ofertas') {
				static::join($query, 'subastas as s', 'p.subasta_id', '=', 's.id' );
				$query->orderBy("s.fecha_fin_ofertas", $valor);
			}
        }
    }

    static public function generarConsultaFiltros(string $valor, ?string $descripcion = null, array $filtros = [], array $ordenes = []) {
        $query = static::generarConsultaBase();
        $descripcion = $descripcion ?? $valor;
        $query->selectRaw("DISTINCT {$valor} AS valor, {$descripcion} AS descripcion");

        static::aplicarFiltros($query, array_merge($filtros, ['vigente' => 1]));
        static::aplicarOrdenes($query, $ordenes);
        return $query->get()->toArray();
    }

    static public function obtenerFiltrosDisponibles(array $filtros = []): array {

        $disponibles = [
            'provincias'       => static::generarConsultaFiltros('p.provincia', null, $filtros),
            'monedas'          => static::generarConsultaFiltros('p.moneda'   , null            , $filtros),
            'colores'          => static::generarConsultaFiltros('p.color'    , null            , $filtros),
            'puertas'          => static::generarConsultaFiltros('p.puertas'  , null            , $filtros, ['puertas'=>'asc']),
            'tiposCombustible' => static::generarConsultaFiltros('tc.id'      , 'tc.descripcion', $filtros),
            'condicion'        => static::generarConsultaFiltros('p.condicion', null            , $filtros),
            'tiposVendedor'    => static::generarConsultaFiltros('ur.id'      , 'ur.descripcion', array_merge($filtros, [
                'usuario_rol_id' => [
                    Rol::USUARIO_AGENCIA,
                    Rol::USUARIO_PARTICULAR
                ]
            ])),
        ];

        $disponibles['localidades'] = !empty($filtros['provincia']) ?
			Publicacion::generarConsultaFiltros('p.localidad', 'p.localidad', $filtros) :
			[];

        return $disponibles;
    }

    static public function obtenerMarcasDisponibles(array $filtros) {
        return static::generarConsultaFiltros('p.marca', 'p.marca', $filtros);
    }

    static public function obtenerModelosDisponibles(array $filtros) {
        return static::generarConsultaFiltros('p.modelo', 'p.modelo', $filtros);
    }

    /**
     * @deprecated Utilizar \App\Modules\Publicacion\PublicacionBusiness::obtenerUrlMarketplace($publicacionId)
     */
    public function obtenerUrlSpa() {
		return config("app.spa_url") . '/publicaciones/' . $this->id;
	}
    
    public function obtenerNombreVehiculo(): string {
        return implode(' ', [$this->marca, $this->modelo, $this->año]);
    }

    public static function misPublicacionesSpaPage() {
		return config("app.spa_url") . '/publicaciones/mis-publicaciones';
	}
    
    public function esCeroKm(): bool {
        return $this->condicion === static::CONDICION_0KM;
    }

    public function obtenerAgenciaId(): int {
        return $this->usuario->obtenerAgenciaId();
    }
}
