<?php

namespace App\Modules\Financiacion\Modules\Solicitud;

use App\Base\BusinessException;
use App\Modules\Financiacion\Modules\Solicitud\Dtos\ActualizarDatosFinanciacionDto;
use App\Modules\Parametros\Parametro;
use App\Modules\Prendarios\Clients\PrendariosAdapter;
use App\Modules\Prendarios\Factory;
use App\Modules\Prendarios\Models\CodigoPostal;
use App\Modules\Prendarios\Models\EstadoCivil;
use App\Modules\Prendarios\Models\Localidad;
use App\Modules\Publicaciones\Publicacion;
use App\Modules\Users\Models\Rol;
use App\Modules\Users\Models\User;
use DateTime;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use function count;
use function strtoupper;

class SolicitudBusiness {
	const ID_TELEFONO_CONTACTO_PLATAFORMA = 11;
    const ID_SUCURSAL_DIGITAL = 12;

	private ?User        $solicitante = null;
    private ?Publicacion $publicacion = null;

    public function __construct(?User $solicitante, ?Publicacion $publicacion = null) {
        $this->solicitante = $solicitante;
        $this->publicacion = $publicacion;
    }

    private function obtenerConector(): PrendariosAdapter {
        if ($this->publicacion) {
            $cliente = Factory::crearPorPublicacion($this->publicacion);
        } else {
            $cliente = Factory::crearPorUsuarioConfigurado();
        }
        return new PrendariosAdapter($cliente);
    }

    public function obtenerCuotasYMontos(?int $capital){
        $montoMaximo = $this->obtenerMontoMaximoFinanciable();
        $cuotas      = $this->obtenerCuotas($capital);

        return [
            'cuotas'      => $cuotas,
            'montoMaximo' => $montoMaximo,
        ];

    }

    public function obtenerMontoMaximoFinanciable() {
        $maximoFinanciable = $this->obtenerConector()->obtenerMontoMaximoFinanciable($this->publicacion->año, $this->publicacion->codia);

        return $maximoFinanciable;
    }

	public function getMaxLtv() {
		return $this->obtenerConector()->getMaxLtv($this->publicacion->año);

	}

    public function obtenerCuotas(?int $monto) {
		$montoMaximoFinanciable = $this->obtenerMontoMaximoFinanciable();
		if ($monto && $monto > $montoMaximoFinanciable) {
			throw new BusinessException('El monto a financiar es mayor que el permitido');
		}

        //@todo: revisar si acá se puede utilizar el usuario configurado.
		$response = $this->obtenerConector()->obtenerPlazos(
            $this->publicacion->año,
			$monto ?? $montoMaximoFinanciable,
		);

		$planesCuotas = [];
		foreach ($response['amounts'] as $cuotas => $montos) {

            // Dado que los valores vienen como un string, lo pasamos
            // a número, sin el . que es separador decimal para php.
            $montos = array_map(function($valor) {
                return str_replace('.','', $valor)/1;
            }, $montos);

            $planesCuotas[] = [
                'cuotas' => $cuotas,
                'montoMinimo' => min($montos),
                'montoMaximo' => max($montos),
            ];
		}

        $numerosCuotas = [];
        foreach ($planesCuotas as $planCuotas){
            $numerosCuotas = array_merge($numerosCuotas, [$planCuotas['cuotas']]);
        }
        sort($numerosCuotas);
        $planesCuotasOrdenado = [];
        foreach ($numerosCuotas as $numeroCuotas){
            foreach ($planesCuotas as $planCuota){
                if($numeroCuotas === $planCuota['cuotas']){
                    $planesCuotasOrdenado = array_merge($planesCuotasOrdenado, [$planCuota]);
                }
            }
        }

		return $planesCuotasOrdenado;
	}

    public function obtenerCuotasPorUsuario(int $capital): array {

        $agenciaId   = $this->publicacion->usuario->obtenerAgenciaId();
        $codigoPostal = CodigoPostal::getOne(['CodigoPostal' => $this->solicitante->codigo_postal]);
		$maximoMontoFinanciable = $this->getMaxLtv()['MaxFin'];

        $response = $this->obtenerConector()->obtenerPlazosPorAgenciaId(
            $agenciaId,
            $this->publicacion->brand_id,
            $this->publicacion->codia,
            $this->publicacion->año,
            $this->publicacion->esCeroKm(),
            $this->publicacion->precio_sugerido,
            $this->obtenerProductoFinancieroId(),
            $this->solicitante->uso_vehiculo,
            $capital,
            $this->solicitante->codigo_postal,
            $codigoPostal->provincia->Codigo,
            "{$codigoPostal->NombreLocalidad} ({$codigoPostal->provincia->Nombre})", 		// Requiere que sea un valor de /resources/search/postal-code?cp=2000
            0,
			$maximoMontoFinanciable,
            $this->solicitante->dni,
            $this->solicitante->sexo,
        );

		$cuotas = [];

		foreach ($response['productos_grid'] as $cantidadCuotas => $valoresCuotas) {

            $valoresCuotas = array_map(function($valor) { return str_replace('.', '', $valor)/1; }, $valoresCuotas);

			$cuotas[] = [
                'cantidadCuotas'     => $cantidadCuotas/1,
                'valoresCuotas'      => $valoresCuotas,
                'valorPrimerCuota'   => $valoresCuotas[0],
                'valorCuotaPromedio' => round(array_sum($valoresCuotas) / count($valoresCuotas)),
            ];
		}

        $numerosCuotasHabilitadas = explode(',',$response['plazos_habilitados']);
        sort($numerosCuotasHabilitadas);
        $cuotasHabilitadas = [];
        foreach ($numerosCuotasHabilitadas as $numeroCuotas) {
            foreach ($cuotas as $cuota) {
                if($cuota['cantidadCuotas'] == $numeroCuotas){
                    $cuotasHabilitadas = array_merge( $cuotasHabilitadas, [ $cuota ]);
                }
            }
        }

		return [
			'cuotas' => $cuotasHabilitadas,
            'plazos_habilitados' => $response['plazos_habilitados'],
			'seguros' => $response['seguros']
		];
    }

    private function obtenerPlanCuotas(int $capital, int $cantidadCuotas) {
        $data = $this->obtenerCuotasPorUsuario($capital);
        foreach($data['cuotas'] as $planCuotas) {
            if ($planCuotas['cantidadCuotas'] === $cantidadCuotas) {
                return [
                    'planCuotas'         => $planCuotas,
                    'plazos_habilitados' => $data['plazos_habilitados'],
                    'seguros'            => $data['seguros'],
                ];
            }
        }
        throw new BusinessException('Cantidad de cuotas no disponible');
    }

	private function obtenerProductoFinancieroId() {
		$response = $this->obtenerConector()->getProducts($this->publicacion->año, $this->publicacion->brand_id);
		return !empty($response[0]) ? $response[0]['Codigo'] : null;
	}

    public function validarPuedeGenerar(): bool {

        // La primera vez que quiera solicitar un credito, no tendrá estos valores completos,
        // lo que fallará la llamada a ->validarSolicitud()
        // Por tanto, no tiene sentido corroborarlo.
        if (!$this->solicitante->dni || ! $this->solicitante->sexo) {
            return true;
        }
        $respuesta = $this->obtenerConector()->validarSolicitud($this->solicitante->dni, $this->solicitante->sexo);

        if (array_key_exists('preOperacion', $respuesta)) {
            throw new BusinessException('Tiene una solicitud pendiente');
        }

        if (!array_key_exists('situacion', $respuesta)) {
            throw new BusinessException('El formato de DNI no es correcto');
        }

        return true;
    }

    public function generar(int $capital, string $cantidadCuotas, string $cotizacionSeguroId) {

        $this->validarPuedeGenerar();

        $agenciaId         = $this->publicacion->obtenerAgenciaId();
        $data              = $this->obtenerPlanCuotas($capital, $cantidadCuotas);
        $planCuotas        = $data['planCuotas'        ];
		$plazosHabilitados = $data['plazos_habilitados'];
        $seguros           = $data['seguros'           ];
		$valorCuota        = $planCuotas['valorPrimerCuota'  ];
        $cuotaPromedio     = $planCuotas['valorCuotaPromedio'];

        if (empty($seguros)) {
            throw new BusinessException('No hay seguros para cotizar');
        }

        $seguro             = $seguros[0];
        //$cotizacionSeguroId = $seguro['IDCotizacion'];
        $prendariosClient   = $this->obtenerConector();

		try {
			$resultado = $prendariosClient->generarSolicitud(
				$agenciaId,
				$cantidadCuotas,
				$valorCuota,
				$cuotaPromedio,
				$plazosHabilitados,
				$cotizacionSeguroId,
				$capital,
				$this->obtenerProductoFinancieroId($this->publicacion),
				$this->publicacion->dominio,
				$this->publicacion->brand_id,
				$this->publicacion->codia,
				$this->publicacion->año,
				$this->publicacion->precio_sugerido,
				strtoupper($this->solicitante->apellido),
				strtoupper($this->solicitante->nombre),
				$this->solicitante->sexo,
				$this->solicitante->email,
				$this->solicitante->telefono,
				$this->solicitante->dni,
				(string) $this->solicitante->estado_civil_id,
				$this->solicitante->uso_vehiculo,
				$this->solicitante->codigo_postal,
				DateTime::createFromFormat('Y-m-d', $this->solicitante->fecha_nacimiento)->format('d/m/Y')
			);

			$operacion = $prendariosClient->obtenerOperacion($resultado['preOperacion']);
			$this->publicacion->usuario->onboardingUser->business->load('address');

            if($resultado['resolucion'] === 3){
                $businessException = new BusinessException('No se pudo generar la solicitud', [], 422);
                $businessException->setData([
                    'informacionContacto' =>$this->obtenerInformacionContacto(),
                ]);

                throw $businessException;
            }

			return $operacion['CodigoPreOperacion'];
		} catch (Exception $exception) {
			$businessException = new BusinessException('No se pudo generar la solicitud', [], 422, $exception);
			$businessException->setData([
				'informacionContacto' =>$this->obtenerInformacionContacto(),
			]);

			throw $businessException;
		}
    }


	public function obtenerInformacionContacto() {
		return [
			'agencia'    => $this->publicacion->usuario->obtenerTelefonoContacto(),
			'plataforma' => static::obtenerTelefonoContactoPlataforma(),
		];
	}

    public function obtenerDatos(int $operacionId, ?float $monto = null) {
        $prendariosClient = $this->obtenerConector();
        $operacion = $prendariosClient->obtenerOperacion($operacionId);

        // Esto es para evitar que un usuario traiga una operación que no fué
        // solictada por él.
        if ($this->solicitante->dni/1 != $operacion['NroDoc']/1) {
            throw new BusinessException('No se encontró la operación solicitada');
        }

        $this->publicacion->usuario->onboardingUser->business->address->load('province');
        if (!$monto) {
            $monto = $operacion['Capital'];
        }
        $seguro = $operacion['Seguro'];

        return [
            'informacionContacto' =>$this->obtenerInformacionContacto(),
            'operacion' => [
                'codigo'     => $operacion['CodigoPreOperacion'],
                'montoTotal' => $monto,
                'cuotas'     => $operacion['Plazo'             ],
                'valorCuota' => $operacion['Cuota'             ],
            ],
            'seguro'      => [
                'Cobertura'   => $seguro['Cobertura'],
                'NomCompania' => $seguro['Aseguradora'],
                'ValorSeguro' => floor($seguro['Valor']),
            ],
            'publicacion' => $this->publicacion,
        ];
    }

    static private function obtenerTelefonoContactoPlataforma() {
        return Parametro::getById(static::ID_TELEFONO_CONTACTO_PLATAFORMA)->valor;
    }

    public function obtenerFechaNacimiento(): string {
        $prendariosClient = $this->obtenerConector();

        $informacionCrediticia = $prendariosClient->obtenerInformacionCrediticia(
            $this->solicitante->apellido,
            $this->solicitante->nombre,
            $this->solicitante->sexo,
            $this->solicitante->email,
            $this->solicitante->telefono,
            $this->solicitante->dni,
            $this->solicitante->estado_civil_id,
            $this->solicitante->codigo_postal
        );

        return $informacionCrediticia->obtenerFechaNacimiento();
    }

    public function obtenerPersonasPorDocumento(string $dni) {
        return $this->obtenerConector()->obtenerPersonasPorDocumento($dni);
    }

    public function actualizarDatosFinanciacion(ActualizarDatosFinanciacionDto $dto) : User {
		if ($this->solicitante->rol_id !== Rol::USUARIO_PARTICULAR) {
			throw new AuthorizationException('No presenta el rol de particular');
		}

        $this->solicitante->codigo_postal   = $dto->codigo_postal;
        $this->solicitante->localidad       = $dto->localidad;
        $this->solicitante->provincia       = $dto->provincia;
        $this->solicitante->telefono        = $dto->telefono;
        $this->solicitante->estado_civil_id = $dto->estado_civil_id;
        $this->solicitante->uso_vehiculo    = $dto->uso_vehiculo;

        // Una vez que el usuario tiene dni cargado
        // los datos asociados no pueden modificarse.
        if (!$this->solicitante->dni) {
            $this->solicitante->dni      = $dto->dni;
            $this->solicitante->nombre   = $dto->nombre;
            $this->solicitante->apellido = $dto->apellido;
            $this->solicitante->sexo     = $dto->sexo;

            $fechaNacimiento = $this->obtenerFechaNacimiento();
            $this->solicitante->fecha_nacimiento = $fechaNacimiento;
        }

        $this->solicitante->guardar();

        return $this->solicitante;
    }

    public static function listarEstadosCiviles() {
        return EstadoCivil::listar();
    }

    public static function listarLocalidades(int $page, int $limit, array $filtros = [], array $ordenes = [], $opciones = []) {
        return Localidad::listar($page, $limit, $filtros, $ordenes, $opciones);
    }

    public static function listarUsosVehiculo() {
        return [
            [ 'name' => 'Particular', 'id' => User::USO_VEHICULO_PARTICULAR],
            [ 'name' => 'Remis', 'id' => User::USO_VEHICULO_REMIS]
        ];
    }

    public function notificarErrorAlSolicitarCuotasPorUsuario(){
        $this->notificarError(new ErrorAlSolicitarCuotasPorUsuario($this->publicacion->usuario->email, $this->publicacion->id, $this->publicacion->usuario->obtenerNombreVendedor()));
    }


    private static function obtenerEmailSucursalDigital(){
        return Parametro::getById(static::ID_SUCURSAL_DIGITAL)->valor;
    }

    public function notificarPoductoFaltante() {
        $this->notificarError(new NotificarProductoFaltanteEmail($this->publicacion->usuario->email, $this->publicacion->id, $this->publicacion->usuario->obtenerNombreVendedor()));
    }

    private function notificarError(Mailable $mensaje) {

        $emails = [];

        $users = $this->obtenerUsuariosAdmnistradores();
        foreach ($users as $user) {
           $emails[] = $user->email;
		}
        $emailSucursalDigital = static::obtenerEmailSucursalDigital();
        $emails[] = $emailSucursalDigital;

        Mail::to($emails)->send($mensaje);

    }

    private function obtenerUsuariosAdmnistradores() {
        return User::listar(
            1,
            0,
            [
                'rol_id' => ROL::ADMINISTRADOR,
                'estado' => USER::HABILITADO],
            [],
            [
                'onboardingUser.userPersonalData',
                'onboardingUser.business'
            ]
        );
    }

}
