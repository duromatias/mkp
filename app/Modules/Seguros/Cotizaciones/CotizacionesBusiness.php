<?php

namespace App\Modules\Seguros\Cotizaciones;

use App\Modules\Prendarios\Clients\PrendariosAdapter;
use App\Modules\Prendarios\Factory;
use App\Modules\Prendarios\Models\Localidad;
use App\Modules\Seguros\Client\SegurosFactory;

class CotizacionesBusiness {

    static public function listar(int $codia, int $anio, int $cp, string $localidad, string $provincia){
        $cliente = SegurosFactory::crear();
        $seguros = $cliente->listarSeguros(
            $codia,
            $anio,
            $cp,
            $localidad,
            $provincia
        );

        $respuesta = [];

        foreach ($seguros as $seguro) {

            if (array_key_exists('nombre_compania', $seguro) && array_key_exists('coberturas', $seguro) && $seguro['coberturas']) {

                $nombre_compania = $seguro['nombre_compania'];
                $url_imagen = $seguro['url_imagen'];

                if (count($seguro['coberturas']) > 0) {
                    foreach ($seguro['coberturas'] as $cobertura) {
                        $itemsCobertura = [];
                        if(is_array($cobertura['items_cobertura'])){
                            $itemsCobertura = $cobertura['items_cobertura'];
                        }
                        $respuesta[] = [
                            'nombre_compania' => $nombre_compania,
                            'url_imagen' => $url_imagen,
                            'tipo_cobertura' => $cobertura['tipo'],
                            'descripcion_cobertura' => $cobertura['subtipo_cobertura'],
                            'items_cobertura' => $itemsCobertura,
                            'valor_seguro' => $cobertura['premio'],
                            'suma_asegurada' => $cobertura['valor_asegurado'],
                        ];
                    }
                }
            }
        }

        return $respuesta;
    }

    static public function listarAños(): array {
        $años = [];
        $año = 2007;
        while($año <= date('Y')/1) {
            $años[] = [
                'valor' => $año,
            ];
            $año++;
        }
        return $años;
    }

    static public function listarModelosPorAño(int $año, ?string $busqueda = null): array {
        $prendariosClient = Factory::crearPorUsuarioConfigurado();
        $prendariosAdapter = new PrendariosAdapter($prendariosClient);
        return $prendariosAdapter->obtenerModelosPorAnio($año,$busqueda);
    }

    public static function listarLocalidades(int $page, int $limit, array $filtros = [], array $ordenes = [], $opciones = []) {
        return Localidad::listar($page, $limit, $filtros, $ordenes, $opciones);
    }

    static public function obtenerRivadaviaTipo(string $año, string $codigo){
        return Factory::crearPorUsuarioConfigurado()->get("/seguros/getrivadaviatipo/{$año}/{$codigo}");
    }
}
