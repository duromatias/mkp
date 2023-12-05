<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Modules\Publicaciones\MisPublicaciones;

use App\Base\BusinessException;
use App\Base\Debug\MedicionDeTiempos;
use App\Modules\Parametros\Parametro;
use App\Modules\Prendarios\Vehiculos\VehiculosBusiness as PrendariosBusiness;
use App\Modules\Financiacion\Businesses\FinanciacionBusiness;
use App\Modules\Publicaciones\MisPublicaciones\Emails\ErrorAlPublicarEnS1;
use App\Modules\Publicaciones\MisPublicaciones\Emails\PublicacionVencidaEmail;
use App\Modules\Publicaciones\MisPublicaciones\Emails\PublicacionVencimientoProximoEmail;
use App\Modules\Publicaciones\Multimedia\MultimediaBusiness;
use App\Modules\Publicaciones\Publicacion;
use App\Modules\Publicaciones\PublicacionesBusiness;
use App\Modules\Subastas\Business\SubastasBusiness;
use App\Modules\Users\Business\UserBusiness;
use App\Modules\Users\Models\User;
use App\Modules\Vehiculos\Business\VehiculosBusiness;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

/**
 * Description of MisPublicacionesBusines
 *
 * @author kodear
 */
class MisPublicacionesBusiness extends PublicacionesBusiness {
    
    static public function obtenerFiltrosUsuario(int $userId): array {
        $user = User::getById($userId);
        $user->load('onboardingUser.business');
        if ($user->esAgencia()) {
            return [
                'business_id' => $user->onboardingUser->business->id,
            ];
        }
        
        if ($user->esParticular()) {
            return [
                'usuario_id' => $user->id,
            ];
        }
        
        if ($user->esAdministrador()) {
            return [];
        }
        
        throw new BusinessException('No permitido');
    }
    
    static public function getById(int $userId, $publicacionId): Publicacion {
        $filtros = static::obtenerFiltrosUsuario($userId);
        return Publicacion::getById($publicacionId, $filtros, static::getOpciones($userId));
    }
    
    static public function validarPuedeActualizar(int $userId, int $publicacionId): bool {
        $publicacion = static::getById($userId, $publicacionId);

		if ($publicacion->subasta && $publicacion->subasta->finalizada()) {
			throw new BusinessException('No es posible realizar la acción debido a que la publicación pertenece a una subasta finalizada');
		}

        return true;
    }
    
    static public function obtenerPrecioMinimo(string $moneda): float {
        if ($moneda === Publicacion::MONEDA_PESOS) {
            return Parametro::valorPrecioMinimoPublicacionPesos();
        } 
        
        if ($moneda === Publicacion::MONEDA_DOLARES) {
            return Parametro::valorPrecioMinimoPublicacionDolares();
        }
        
        throw new BusinessException('Moneda incorrecta');
    }
    
    static public function validarAnio($id, $anio): bool {
        $publicacion = Publicacion::getById($id);
        return VehiculosBusiness::validarAño($publicacion->codia, $anio);
    }
    
    static public function listar(int $userId, int $offset = 0, int $limit = 10, array $filtros = [], array $ordenes = [], array $opciones = []) {
        $filtrosUsuario = static::obtenerFiltrosUsuario($userId);
        $ordenes = array_merge($ordenes, ['updated_at' => 'desc' ]);
        
        $filtros = array_merge(
            $filtros, 
            $filtrosUsuario,
        );
        
        $data = [
            'listado'            => Publicacion::listar($offset, $limit, $filtros, $ordenes, static::getOpciones($userId)),
            'filtrosDisponibles' => Publicacion::obtenerFiltrosDisponibles($filtrosUsuario),
        ];
        
        $data['filtrosDisponibles']['localidades'] = [];
        
        if (!empty($filtros['provincia'])) {
            $data['filtrosDisponibles']['localidades'] = Publicacion::generarConsultaFiltros('p.localidad', 'p.localidad', array_merge([
                'provincia' => $filtros['provincia'],
            ], $filtros));
        }
        
        return $data;
    }

	static public function crearPorParticular(CrearPublicacionDto $publicacionDto, User $user) {
    	if ($publicacionDto->subasta_id) {
    		throw new BusinessException('Rol no autorizado a crear una publicación asignada a una subasta');
		}

        $medicion = MedicionDeTiempos::comenzar("Crear publicacion por particular");

        $ret = DB::transaction(function() use ($publicacionDto, $user) {
			if (!$user->telefonoCargado()) {
				$user->telefono = $publicacionDto->telefono;

				$user->guardar();
			}

			if (!$user->direccionCargada()) {
				$user->actualizarDireccion(
					$publicacionDto->calle,
					$publicacionDto->numero,
					$publicacionDto->codigo_postal,
					$publicacionDto->localidad,
					$publicacionDto->provincia,
					$publicacionDto->latitud,
					$publicacionDto->longitud
				);
			}

			return static::crear($publicacionDto, $user);
		});
        
        $medicion->terminar();
        
        return $ret;
	}

	static public function crearPorAgencia(CrearPublicacionDto $publicacionDto, User $user): Publicacion {
		if ($publicacionDto->subasta_id && (!$user->onboardingUser->business || !$user->onboardingUser->business->ready_at)) {
			throw new BusinessException('Debe completar sus datos en la plataforma de Onboarding antes de poder asignar la publicación a una subasta');
		}

        $medicion1 = MedicionDeTiempos::comenzar("Crear publicacion por Agencia");
        
		$publicacion = DB::transaction(function() use ($publicacionDto, $user) {
			if (!$user->telefonoCargado()) {
				throw new BusinessException('El usuario no tiene un teléfono de contacto, no podrá realizar la carga de la publicación');
			}

			return static::crear($publicacionDto, $user);
		});
        
        // Para publicar en S1 la llamda debe hacerse desde la web del marketplace
        if ($publicacionDto->solicitadoDesdeMarketplace() && $user->esAgenciaReady()) {
            try {
                $medicion2 = MedicionDeTiempos::comenzar('Publicar en S1');
                static::publicarEnS1($publicacion);
            } catch (\Exception $ex) {
                
                // Si ocurre un error, se notifica vía email.
                static::notificarErrorAlPublicarEnS1($publicacion);
            } finally {
                $medicion2->terminar();
            }
        }
        
        $medicion1->terminar();
        
        return $publicacion;
	}

    static public function crear(CrearPublicacionDto $dto, User $user) {
        $datosModelo = VehiculosBusiness::getOneModelo($dto->codia);
        $precioSugerido = static::obtenerPrecioSugerido($dto->codia, $dto->año);

		$publicacion = Publicacion::crear($dto, $user->id, $datosModelo->brand->name, $datosModelo->description, $precioSugerido);
        FinanciacionBusiness::sincronizarFinanciacion($publicacion, $dto->financiacion, $user);
        
        MultimediaBusiness::agregarArchivos($publicacion->id, $dto->obtenerArchivosNuevos());
        MultimediaBusiness::actualizarPortada($publicacion->id, $dto->portada_index);
        
		$publicacion->load(['tipoCombustible', 'subasta']);

		return $publicacion;
    }
    
    static public function obtenerRutasFotos(Publicacion $publicacion): array {
        $archivos = [];
        foreach ($publicacion->multimedia()->get() as $multimedia) {
            if ($multimedia->esImagen()) {
                $archivos[] = MultimediaBusiness::getFullPath($multimedia->id, $multimedia->extension, 'card');
            }
        }
        
        return $archivos;
    }
    
    static public function publicarEnS1(Publicacion $publicacion) {
        return PrendariosBusiness::crear(
            $publicacion->usuario,
            (int)  $publicacion->codia,
            (int)  $publicacion->año,
            (int)  $publicacion->kilometros,
			$publicacion->dominio,
            static::obtenerRutasFotos($publicacion),
        );
    }
    
    static public function notificarErrorAlPublicarEnS1(Publicacion $publicacion) {
        $rs = UserBusiness::listarAdministradores();
        $emails = [];
    	foreach ($rs as $usuario) {
            $emails[] = $usuario->email;
		}
        
    	Mail::to($emails)->send(new ErrorAlPublicarEnS1($publicacion));
    }
    
    static public function actualizar(int $userId, int $publicacionId, CrearPublicacionDto $dto): Publicacion {
        static::validarPublicacionSinOfertas($publicacionId);

        $user = User::getById($userId);

        if ($user->esParticular() && $dto->subasta_id) {
			throw new BusinessException('Rol no autorizado a asignar una publicación a una subasta');
		}

        if ($user->esAgencia() && $dto->subasta_id && (!$user->onboardingUser->business || !$user->onboardingUser->business->ready_at)) {
			throw new BusinessException('Debe completar sus datos en la plataforma de Onboarding antes de poder asignar la publicación a una subasta');
		}

        $publicacion    = static::getById($userId, $publicacionId);
		$precioSugerido = static::obtenerPrecioSugerido($dto->codia, $dto->año);
        
        DB::beginTransaction();
        $publicacion = $publicacion->actualizar($dto, $precioSugerido);
        
        FinanciacionBusiness::sincronizarFinanciacion($publicacion, $dto->financiacion);
        DB::commit();
        
        MultimediaBusiness::sincronizar($publicacionId, $dto->obtenerArchivosExistentes());
        MultimediaBusiness::agregarArchivos($publicacionId, $dto->obtenerArchivosNuevos());
        MultimediaBusiness::actualizarPortada($publicacionId, $dto->portada_index);

		$publicacion->load(['tipoCombustible', 'subasta']);

		return $publicacion;
    }

    static public function eliminar(int $userId, int $publicacionId, string $motivo) {
        $publicacion = static::getById($userId, $publicacionId);

        if ($publicacion->subasta) {
			$user = User::getById($userId);

			if ($user->esAgencia()) {
				static::validarPublicacionSinOfertas($publicacionId);
			}
		}

		$publicacion->eliminar($motivo);
    }
    
    static private function obtenerPrecioSugerido(string $codia, int $anio) {

        try {
            return VehiculosBusiness::getPrecioModelo($codia, $anio);
        } catch (\Exception $e) {
            // no hacemos nada..
        }
        return 0;
        
    }

    static public function validarPublicacionSinOfertas(int $publicacionId) {
    	$publicacion = Publicacion::getById($publicacionId);

    	if (count($publicacion->ofertas) > 0) {
    		throw new BusinessException('No es posible realizar la acción debido a que la publicación presenta ofertas activas');
		}
	}

    static public function notificarPublicacionesProximasVencer() {
    	$diasProximosVencer = Parametro::getById(Parametro::ID_PUBLICACION_PROXIMA_VENCER);
    	$diasPerderVigencia = Parametro::getById(Parametro::ID_VIGENCIA_PUBLICACIÓN);

    	$dias = $diasPerderVigencia->valor - $diasProximosVencer->valor;

		$publicaciones = Publicacion::listarTodos([
    		'updated_at' => (new DateTime())->modify("-{$dias} day")->format('Y-m-d')
		]);

    	foreach ($publicaciones as $publicacion) {
    		Mail::to($publicacion->usuario->email)->send(new PublicacionVencimientoProximoEmail($publicacion));
		}
	}

	static public function notificarPublicacionesVencidas() {
		$dias = Parametro::getById(Parametro::ID_VIGENCIA_PUBLICACIÓN)->valor;

		$publicaciones = Publicacion::listarTodos([
			'updated_at' => (new DateTime())->modify("-{$dias} day")->format('Y-m-d')
		]);


		foreach ($publicaciones as $publicacion) {
			Mail::to($publicacion->usuario->email)->send(new PublicacionVencidaEmail($publicacion));
		}
	}

	public static function getOpcionesFormulario() {
		return [
			'precio_minimo' => [
				Publicacion::MONEDA_PESOS   => MisPublicacionesBusiness::obtenerPrecioMinimo(Publicacion::MONEDA_PESOS  ),
				Publicacion::MONEDA_DOLARES => MisPublicacionesBusiness::obtenerPrecioMinimo(Publicacion::MONEDA_DOLARES),
			],
			'colores' => VehiculosBusiness::listarColores(),
			'tipos_combustible' => VehiculosBusiness::listarTiposCombustible(),
			'marcas' => VehiculosBusiness::listarMarcas([]),
			'subasta_disponible' => SubastasBusiness::obtenerDisponible()
		];
	}
}
