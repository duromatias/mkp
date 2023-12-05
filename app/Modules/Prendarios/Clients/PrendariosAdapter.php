<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Modules\Prendarios\Clients;

class PrendariosAdapter {

    private PrendariosClient $cliente;

    public function __construct(PrendariosClient $cliente) {
        $this->cliente = $cliente;
    }

    public function obtenerLimitesFinanciacion(int $año, int $isVip) {
		return $this->cliente->get('/agencias/lite/simulador/getMaxLtv', [
			'anio'		=> $año,
			'isVip'		=> $isVip,
		]);
	}

    public function obtenerMontoMaximoFinanciable(int $año, int $codia) {
        return $this->cliente->get('/agencias/lite/simulador/getMaxFinancialValue', [
            'anio'  => $año,
            'codia' => $codia,
        ])['content']['MaxFinancial'];
    }

    public function obtenerPlazos(int $año, int $montoMaximoFinanciable) {
        return $this->cliente->get('/agencias/lite/simulador/gridData', [
			'anio'		=> $año,
			'monto'		=> $montoMaximoFinanciable,
			'amountVip' => 1,
			'retorno' 	=> 0
		]);
    }

    private function _obtenerPersonasPorDocumento(string $documento): array {
        $respuesta = $this->cliente->get("/agencias/lite/personalData/{$documento}");
        $rs = $respuesta['response']['data'];

        return array_map(function($row) {
            $data = $row['personalData'];
            $data['fullName'] = $data['name'];
            if (!empty($data['lastName'])) {
                $data['fullName'] .= " {$data['lastName']}";
            }
            return $data;
        }, $rs);
    }

    public function obtenerPersonasPorDocumento(string $documento): array {
        $rs = $this->_obtenerPersonasPorDocumento($documento);

        return array_map(function($row) use ($documento) {
			if (isset($row['personalId'])) {
				return $row;
			}

			$row['personalId'] = $documento;
			$rs = $this->_obtenerPersonasPorDocumento($row['taxCode']);

			foreach($rs as $datosPorCUIL) {
				$row['genre'   ] = $datosPorCUIL['genre'   ];
				$row['name'    ] = $datosPorCUIL['name'    ];
				$row['lastName'] = $datosPorCUIL['lastName'];
				$row['fullName'] = $datosPorCUIL['fullName'];
				return $row;
			}
        }, $rs);
    }

    public function obtenerPlazosPorAgenciaId(
        int    $agenciaId,
        int    $brandId,
        int    $codia,
        int    $año,
        bool   $ceroKm,
        int    $precioMercado,
        string $products,
        int    $use,                // isVip ?? o si es Particular o Remis?
        int    $capital,
        string $codigoPostal,
        int    $provinciaId,
        string $calle,
        int    $retorno,
		int    $maximoMontoFinanciable,
        int    $dni,
        string $sexo
    ): array {
		$data = [
			'brands'       => $brandId, // brand_id
			'models'       => $codia, // codia
			'years'        => "{$año}-{$precioMercado}", // Año-PrecioMercado
			'ceroKm'       => $ceroKm,
			'products'     => "{$products}", // Obtenido de GET https => //s1.decreditoslabs.com/simulador/products/2017/19?modAgencia=1
			'use'          => $use, // $use ?? o si es Particular o Remis?
			'capital'      => $capital,
			'maxFinVip'    => $maximoMontoFinanciable,
			'postal_codes' => "{$codigoPostal}",
			'provinces'    => "{$provinciaId}",
			'streets'      => $calle,
			'_agency_id'   => $agenciaId, // Averiguar de dÃ³nde sale
			'salary'       => 0,
			'retorno'      => $retorno, // Del producto financiero
			'dni'          => $dni,
			'sexo'         => $sexo
		];
        //echo "pidiendo con shell...\n";
        // return $this->cliente->shell('POST', '/simulador/getPlazos', json_encode($data), []);
        // echo "Haciendo peticion\n";
        return $this->cliente->post('/simulador/getPlazos', $data)['content'];
    }

    public function obtenerInformacionCrediticia(
        string $apellido,
        string $nombres,
        string $sexo,
        string $email,
        string $telefonoCelular,
        int    $numeroDocumento,
        int    $estadoCivilId,
        string $codigoPostal
    ): InformacionCrediticia {
        return new InformacionCrediticia($this->cliente->post('/simulador/checkPersona', [
            'cliente' => [
                'Apellido'        => $apellido,
                'Nombres'         => $nombres,
                'Sexo'            => $sexo,
                'Email'           => $email,
                'Celular'         => $telefonoCelular,
                'NroDoc'          => $numeroDocumento,
                'TipoDoc'         => 1,
                'EstadoCivil'     => $estadoCivilId,
                'CodPostal'       => $codigoPostal,
                'FechaNacimiento' => null,
                'observacion'     => '',
                'conyuge'         => [
                    'NroDoc'   => null,
                    'TipoDoc'  => null,
                    'Sexo'     => null,
                    'Apellido' => null,
                    'Nombres'  => null
                ]
            ],
            'garante' => [
                'NroDoc'   => null,
                'Sexo'     => null,
                'Apellido' => null,
                'Nombres'  => null
            ]
        ]));
    }

    public function generarSolicitud(
        int    $agenciaId,
		string    $cantidadCuotas,
		string $valorCuota,
        string $cuotaPromedio,
		string  $plazosHabilitados,
		?string $insuranceQuote,
		int    $capitalAFinanciar,
		?string $carProduct,
		?string $dominio,
		int    $brandId,
		int    $codia,
		int    $año,
        int    $precioMercado,
        string $apellido,
        string $nombres,
        string $sexoMF,
        string $email,
        string $numeroCelular, //(999)9999999
        string $numeroDocumento,
        string $estadoCivilId,
		int    $particular,
        string $codigoPostal,
        string $fechaNacimiento // dd/mm/yyyy
    ): array {

		$data = [
			'ceroKm'             => false,
			'dominio'            => $dominio,
			'plazos_habilitados' => $plazosHabilitados,
			'primer_cuota'       => str_replace('.', '', $valorCuota   ),
			'cuota_promedio'     => str_replace('.', '', $cuotaPromedio),
			'agency_id'          => $agenciaId,
			'value_vehicle'      => '',
			'car_capital'        => $capitalAFinanciar,
			'modulo_agencias'    => 1,
			'salaryDeclared'     => 0,
			'typeSalaryDeclared' => null,
			'checkReport'        => null,
			'insuranceQuote'     => $insuranceQuote,
			'car_usage'          => $particular === 1 ? 'Particular' : 'Remis',
			'brand'              => $brandId,
			'car_model'          => $codia,
			'car_year'           => "{$año}-{$precioMercado}",
			'car_product'        => $carProduct,
			'car_term'           => $cantidadCuotas, // 36
			'cliente' => [
				'Apellido'        => $apellido,
				'Nombres'         => $nombres,
				'Sexo'            => $sexoMF,
				'Email'           => $email,
				'Celular'         => $numeroCelular,
				'NroDoc'          => $numeroDocumento,
				'TipoDoc'         => 1,
				'EstadoCivil'     => $estadoCivilId,
				'CodPostal'       => $codigoPostal,
				'FechaNacimiento' => $fechaNacimiento,
				'observacion'     => '',
				'conyuge'      => [
					'NroDoc'   => null,
					'TipoDoc'  => null,
					'Sexo'     => null,
					'Apellido' => null,
					'Nombres'  => null
				]
			],
			'garante' => [
				'NroDoc'   => null,
				'Sexo'     => null,
				'Apellido' => null,
				'Nombres'  => null
			]
		];

        return $this->cliente->post('/simulador/postOperacionLite', $data)['resolucion'];
    }

    public function obtenerOperacion(int $codigoOperacion): array {
        return $this->cliente->get("/operations/{$codigoOperacion}")['result'];
    }

	public function getProducts(int $year, int $brandId) {
		return $this->cliente->get("/simulador/products/{$year}/{$brandId}", [
            'modAgencia' => '1'
        ]);
	}

	public function getMaxLtv(int $anio) {
		return $this->cliente->get('/agencias/lite/simulador/getMaxLtv', [
			'anio' => $anio,
			'type' => 'GA'
		]);
    }

    public function validarSolicitud(int $dni, string $sexo): array {
        return $this->cliente->post('/agencias/lite/validacion', [
            'dni'   => $dni,
            'genre' => $sexo,
        ])['data'];
    }

    public function obtenerModelosPorAnio(int $año, string $busqueda): array {
        $rs =  $this->cliente->get('agencias/lite/simulador/getModelsByYear', [
            'query' => $busqueda,
            'year' => $año,
        ]);

        return array_map(function($item) {
            return [
                'label' => $item['label'],
                'codia' => $item['value']['models'],
                'value' => $item['value'],
            ];
        }, $rs);
    }
}
