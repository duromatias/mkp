<?php

namespace App\Modules\Google\Places;

class Ubicacion {
  
    public ?string $localidad     = null;
    public ?string $provincia     = null;
    public ?string $calle         = null;
    public ?string $numero        = null;
    public ?string $departamento  = null;
    public ?string $codigo_postal = null;

    const TYPES = [
        'street_number',
        'route',
        'intersection',
        'locality',
        'administrative_area_level_3',
        'administrative_area_level_2',
        'administrative_area_level_1',
        'postal_code',
    ];


    static public function parse(array $addressComponents): self {
        
        $numero        = null;
        $calle         = null;
        $localidad     = null;
        $departamento  = null;
        $provincia     = null;
        $codigo_postal = null;
        
        $ubicacion = new static;

        foreach ($addressComponents as $addressComponent) {
            
            foreach (static::TYPES as $type) {
                
                if (!in_array($type, $addressComponent->types)) {
                    continue;
                }
                
                $value = $addressComponent->long_name;
                
                switch ($type) {
                    case 'street_number':
                        $numero = $value;
                        break;
                    
                    case 'route':
                    case 'intersection':
                        $calle = $value;
                        break;
                    
                    case 'locality':
                    case 'administrative_area_level_3':
                        $localidad = $value;
                        break;
                    
                    case 'administrative_area_level_2':
                        $departamento = $value;
                        break;
                    
                    case 'administrative_area_level_1':
                        $provincia = $value;
                        break;
                    
                    case 'postal_code':
                        $codigo_postal = $value;
                        break;

                }
            }
        }
        
        $ubicacion->localidad     = ($localidad === null) ? $provincia : $localidad;
        $ubicacion->provincia     = $provincia;
        $ubicacion->departamento  = ($departamento === null) ? $provincia : $departamento;
        $ubicacion->calle         = $calle;
        $ubicacion->numero        = $numero;
        $ubicacion->codigo_postal = $codigo_postal;
                
        return $ubicacion;
    }
}
