<?php

namespace App\Modules\Publicaciones\MisPublicaciones;

use App\Http\FormRequest;
use App\Modules\Publicaciones\Publicacion;
use App\Modules\Publicaciones\Multimedia\MultimediaBusiness;
use App\Modules\Subastas\Business\SubastasBusiness;
use App\Modules\Subastas\Subasta;
use App\Modules\Vehiculos\Business\VehiculosBusiness;
use App\Modules\Publicaciones\MisPublicaciones\Rules\FinanciacionSubastaRule;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CrearPublicacionRequest extends FormRequest {

    public function authorize() {
        return true;
    }

	protected function prepareForValidation()
	{
		$this->merge([
			'financiacion' => $this->input('financiacion') === 'true' || $this->input('financiacion') === true
		]);
	}

    public function rules() {
        return [
            'brand_id'            => 'required|integer',
            'codia'               => 'required|integer',
            'año'                 => 'required|integer',
            'color'               => 'required|string',
            'condicion'           => ['required', Rule::in(Publicacion::CONDICION_OPCIONES)],
            'kilometros'          => 'nullable|integer',
            'puertas'             => ['required', Rule::in(Publicacion::PUERTAS_OPCIONES)],
            'tipo_combustible_id' => 'required|exists:tipos_combustible,id',
            'descripcion'         => 'string|nullable',
            'moneda'              => ['required', Rule::in(Publicacion::MONEDA_OPCIONES)],
            'precio'              => 'required|numeric',
            'calle'               => 'nullable|string',
            'numero'              => 'nullable|string',
            'localidad'           => 'required|string',
            'provincia'           => 'required|string',
            'codigo_postal'       => 'required|string',
            'latitud'             => 'sometimes|numeric|nullable',
            'longitud'            => 'sometimes|numeric|nullable',
            'telefono'            => 'required|numeric',
            'multimedia'          => 'nullable|array',
            'multimedia.*'        => 'required',
            'portada_index'       => 'required|numeric',
			'origen'			  => ['sometimes', Rule::in(Publicacion::ORIGEN_OPCIONES)],
            'subasta_id'          => 'nullable',
            'precio_base'         => 'nullable',
			'financiacion'		  => ['boolean', new FinanciacionSubastaRule($this->input('subasta_id'))],
			'dominio'			  => 'nullable|string|max:7'
        ];
    }
    
    public function customValidations() {
        if ($this->user()->esAgencia()) {
            if (empty($this->post('calle'))) {
                $this->errors()->add('calle', 'Debe completar la calle');
            }
            if (empty($this->post('numero'))) {
                $this->errors()->add('numero', 'Debe completar el número');
            }
        }

        $color = $this->post('color');
        if (!VehiculosBusiness::validarColor($color)) {
            $this->errors()->add('color', 'No se encontró el color');
        }
        
        $precio = $this->post('precio');
        $moneda = $this->post('moneda');
        $precioMinimo = MisPublicacionesBusiness::obtenerPrecioMinimo($moneda);
        if (!($precio >= $precioMinimo)) {
            $this->errors()->add('precio', "El precio mínimo para publicar es {$precioMinimo}");
        }
        
        $this->validarAnio();
        $this->validarArchivos();
        $this->validarSubasta();
    }

    public function validarSubasta() {
    	$subastaId = $this->input('subasta_id');

    	if (!$subastaId) {
    		return;
		}

    	if (!is_numeric($subastaId)) {
			$this->errors()->add('subasta_id', 'Debe ser un valor numérico');
			return;
		}
        
        $this->validarDatosSubasta($subastaId);
    }
    
    protected function validarDatosSubasta(int $subastaId) {

		$subasta = Subasta::getById($subastaId);

		if (!SubastasBusiness::puedeInscribir($subasta)) {
			$this->errors()->add('subasta_id', 'La subasta no se encuentra abierta a inscripción de vehículos');
		};
        
    	$precioBase = $this->input('precio_base');
        
        if (empty($precioBase)) {
            $this->errors()->add('precio_base', 'Este campo es requerido');
            return;
        }
        
        if (!is_numeric($precioBase)) {
            $this->errors()->add('precio_base', 'Debe ser un número');
            return;
        }

    	if ($precioBase && $precioBase < $this->input('precio') * 0.9) {
    		$this->errors()->add('precio_base', 'El precio base es muy bajo');
		}
	}

    
    public function validarAnio() {
        try {
            VehiculosBusiness::validarAño($this->get('codia'), $this->get('año'));
        } catch (\Exception $e) {
            $this->errors()->add('año', 'Año ingresado inválido');
        }
        
    }
    
    public function validarArchivos() {
        $multimedia         = $this->file('multimedia');        
        $tamañoMaximoVideos = MultimediaBusiness::obtenerTamañoMaximoVideos();
        $errors             = $this->getValidatorInstance()->errors();
        
        if (empty($multimedia) || !is_array($multimedia)) {
            $multimedia = [];
        }
        
        $videos = 0;
        $fotos  = 0;
        $portadaIndex = (int) $this->get('portada_index');
        $errorVideoPortada = false;
        $errorVideoGrande  = false;
        
        foreach($multimedia as $index => $archivo) {
            if (!($archivo instanceof UploadedFile)) {
                $errors->add('multimedia', 'Se esperaba un archivo');
            }
            if ($archivo->getError()) {
                $errors->add('multimedia', $archivo->getErrorMessage());
            }
            $permitido = MultimediaBusiness::mimeTypePermitido($archivo);
            if (!$permitido) {
                $errors->add('multimedia', 'Tipo de archivo no permitido');
            }
            $tipo = MultimediaBusiness::obtenerTipo($archivo);
            
            if ($tipo === 'video') {
                if ($portadaIndex === $index) {
                    $errorVideoPortada = true;
                }
                $tamaño = $archivo->getSize();
                if ($tamaño > $tamañoMaximoVideos) {
                    $errorVideoGrande = true;
                }
                $videos++;
            }
            if ($tipo === 'image') {
                $fotos++;
            }
        }
        
        $fotos += $this->cantidadFotosExistentes();
        $videos += $this->cantidadVideosExistentes();
        
        if (!($fotos >= 4)) {
            $errors->add('multimedia', 'Debe subir al menos 4 fotos');
        }
        
        if ($videos > 1) {
            $errors->add('multimedia', 'Sólo puede subir un video');
        }
        
        if ($errorVideoPortada) {
            $errors->add('multimedia', 'El video no puede ser de portada');
        }
        
        if ($errorVideoGrande) {
            $errors->add('multimedia', 'Video demasiado grande');
        }
    }
    
    protected function cantidadFotosExistentes(): int {
        return 0;
    }
    
    protected function cantidadVideosExistentes(): int {
        return 0;
    }
}
