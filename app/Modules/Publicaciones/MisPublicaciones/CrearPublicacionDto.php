<?php

namespace App\Modules\Publicaciones\MisPublicaciones;

use App\Modules\Publicaciones\Publicacion;

class CrearPublicacionDto extends BaseDto
{
	public  int    $brand_id;
    public  string $codia;
    public  int    $año;
    public  string $color;
    public  string $condicion;
    public ?int    $kilometros;
    public  string $puertas;
    public  int    $tipo_combustible_id;
    public ?string $descripcion;
    public  string $moneda;
    public  float  $precio;
    public ?string $calle;
    public ?string $numero;
    public  string $localidad;
    public  string $provincia;
    public  string $codigo_postal;
    public ?float  $latitud = null;
    public ?float  $longitud = null;
    public  string $telefono;
    public  string $origen = Publicacion::ORIGEN_DEUSADOS;
    public ?int    $subasta_id = null;
    public ?float  $precio_base = null;
    public bool    $financiacion = false;
    public ?string $dominio = null;

    public function __construct(array $parameters = []) {
        
        foreach(['subasta_id', 'precio_base'] as $key) {
            if (array_key_exists($key, $parameters)) {
                if (!is_numeric($parameters[$key])) {
                    $parameters[$key] = null;
                }
            }
        }
        
        parent::__construct($parameters);
                
    }

    public static function fromRequest(CrearPublicacionRequest $request) {
		return new self($request->validated());
	}
    
    public function solicitadoDesdeMarketplace(): bool {
        return $this->origen === Publicacion::ORIGEN_DEUSADOS;
    }
}